<?php
/**
 * Controlador de Alojamientos
 * 
 * Maneja el listado, búsqueda y visualización de alojamientos
 */

require_once __DIR__ . '/../Models/Accommodation.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Review.php';
require_once __DIR__ . '/AuthController.php';

class AccommodationController {
    
    /**
     * Modelo de alojamiento
     * @var Accommodation
     */
    private $accommodationModel;
    
    /**
     * Modelo de usuario
     * @var User
     */
    private $userModel;
    
    /**
     * Modelo de reseña
     * @var Review
     */
    private $reviewModel;
    
    /**
     * Controlador de autenticación
     * @var AuthController
     */
    private $auth;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->accommodationModel = new Accommodation();
        $this->userModel = new User();
        $this->reviewModel = new Review();
        $this->auth = new AuthController();
    }
    
    /**
     * Muestra la página principal con todos los alojamientos
     */
    public function index() {
        // Obtener parámetros de búsqueda y filtros
        $busqueda = $_GET['busqueda'] ?? '';
        $ubicacion = $_GET['ubicacion'] ?? '';
        $precioMin = $_GET['precio_min'] ?? '';
        $precioMax = $_GET['precio_max'] ?? '';
        $ratingMin = $_GET['rating_min'] ?? '';
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $porPagina = 9; // 9 alojamientos por página
        
        // Obtener alojamientos con filtros y paginación
        $resultado = $this->accommodationModel->obtenerConFiltros([
            'busqueda' => $busqueda,
            'ubicacion' => $ubicacion,
            'precio_min' => $precioMin,
            'precio_max' => $precioMax,
            'rating_min' => $ratingMin,
            'pagina' => $pagina,
            'por_pagina' => $porPagina
        ]);
        
        $alojamientos = $resultado['alojamientos'];
        $totalAlojamientos = $resultado['total'];
        $totalPaginas = ceil($totalAlojamientos / $porPagina);
        
        // Si el usuario está autenticado, obtener sus favoritos
        $favoritosIds = [];
        if ($this->auth->estaAutenticado()) {
            $favoritos = $this->userModel->obtenerFavoritos($this->auth->getUserId());
            $favoritosIds = array_column($favoritos, 'id');
        }
        
        // Obtener ubicaciones únicas para el filtro
        $ubicaciones = $this->accommodationModel->obtenerUbicaciones();
        
        // Mostrar la vista
        require_once __DIR__ . '/../Views/home/index.php';
    }
    
    /**
     * Agrega un alojamiento a los favoritos del usuario
     */
    public function agregarFavorito() {
        // Verificar que el usuario esté autenticado
        $this->auth->requiereAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/');
            return;
        }
        
        $accommodationId = $_POST['accommodation_id'] ?? null;
        
        if (!$accommodationId) {
            $_SESSION['errores'] = ["ID de alojamiento inválido"];
            $this->redirigir('/');
            return;
        }
        
        // Verificar que el alojamiento existe
        $alojamiento = $this->accommodationModel->buscarPorId($accommodationId);
        if (!$alojamiento) {
            $_SESSION['errores'] = ["El alojamiento no existe"];
            $this->redirigir('/');
            return;
        }
        
        // Agregar a favoritos
        if ($this->userModel->agregarFavorito($this->auth->getUserId(), $accommodationId)) {
            $_SESSION['success'] = "Alojamiento agregado a tus favoritos";
        } else {
            $_SESSION['errores'] = ["Este alojamiento ya está en tus favoritos"];
        }
        
        // Redirigir según de dónde vino
        $redirectTo = $_POST['redirect'] ?? '/';
        $this->redirigir($redirectTo);
    }
    
    /**
     * Elimina un alojamiento de los favoritos del usuario
     */
    public function eliminarFavorito() {
        // Verificar que el usuario esté autenticado
        $this->auth->requiereAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/mi-cuenta');
            return;
        }
        
        $accommodationId = $_POST['accommodation_id'] ?? null;
        
        if (!$accommodationId) {
            $_SESSION['errores'] = ["ID de alojamiento inválido"];
            $this->redirigir('/mi-cuenta');
            return;
        }
        
        // Eliminar de favoritos
        if ($this->userModel->eliminarFavorito($this->auth->getUserId(), $accommodationId)) {
            $_SESSION['success'] = "Alojamiento eliminado de tus favoritos";
        } else {
            $_SESSION['errores'] = ["No se pudo eliminar el alojamiento"];
        }
        
        $this->redirigir('/mi-cuenta');
    }
    
    /**
     * Muestra los detalles de un alojamiento específico
     */
    public function mostrarDetalle($id) {
        $alojamiento = $this->accommodationModel->buscarPorId($id);
        
        if (!$alojamiento) {
            $_SESSION['errores'] = ["Alojamiento no encontrado"];
            $this->redirigir('/');
            return;
        }
        
        // Verificar si es favorito del usuario
        $esFavorito = false;
        if ($this->auth->estaAutenticado()) {
            $esFavorito = $this->userModel->esFavorito($this->auth->getUserId(), $id);
        }
        
        require_once __DIR__ . '/../Views/accommodations/detalle.php';
    }
    
    /**
     * Busca alojamientos según criterios
     */
    public function buscar() {
        $query = $_GET['q'] ?? '';
        $location = $_GET['location'] ?? '';
        
        $alojamientos = [];
        
        if (!empty($query)) {
            $alojamientos = $this->accommodationModel->buscarPorNombre($query);
        } elseif (!empty($location)) {
            $alojamientos = $this->accommodationModel->buscarPorUbicacion($location);
        } else {
            $alojamientos = $this->accommodationModel->obtenerTodos();
        }
        
        // Si el usuario está autenticado, obtener sus favoritos
        $favoritosIds = [];
        if ($this->auth->estaAutenticado()) {
            $favoritos = $this->userModel->obtenerFavoritos($this->auth->getUserId());
            $favoritosIds = array_column($favoritos, 'id');
        }
        
        require_once __DIR__ . '/../Views/accommodations/buscar.php';
    }
    
    /**
     * Agrega una reseña a un alojamiento
     */
    public function agregarReseña() {
        // Verificar autenticación
        $this->auth->requiereAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/');
            return;
        }
        
        $accommodationId = $_POST['accommodation_id'] ?? null;
        $rating = $_POST['rating'] ?? null;
        $comment = trim($_POST['comment'] ?? '');
        
        // Validaciones
        $errores = [];
        
        if (!$accommodationId) {
            $errores[] = "ID de alojamiento inválido";
        }
        
        if (!$rating || $rating < 1 || $rating > 5) {
            $errores[] = "La calificación debe ser entre 1 y 5 estrellas";
        }
        
        if (empty($comment) || strlen($comment) < 10) {
            $errores[] = "El comentario debe tener al menos 10 caracteres";
        }
        
        // Verificar que el alojamiento existe
        if ($accommodationId) {
            $alojamiento = $this->accommodationModel->buscarPorId($accommodationId);
            if (!$alojamiento) {
                $errores[] = "El alojamiento no existe";
            }
        }
        
        // Verificar que el usuario no haya comentado ya
        if ($this->reviewModel->usuarioYaComento($this->auth->getUserId(), $accommodationId)) {
            $errores[] = "Ya has dejado una reseña en este alojamiento";
        }
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $this->redirigir('/');
            return;
        }
        
        // Crear reseña
        if ($this->reviewModel->crear($this->auth->getUserId(), $accommodationId, $rating, $comment)) {
            $_SESSION['success'] = "¡Gracias por tu reseña!";
        } else {
            $_SESSION['errores'] = ["Error al guardar la reseña"];
        }
        
        $this->redirigir('/');
    }
    
    /**
     * Redirige a una URL
     * 
     * @param string $url URL de destino
     */
    private function redirigir($url) {
        header("Location: $url");
        exit;
    }
}
