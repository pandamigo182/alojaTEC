<?php
/**
 * Controlador de Administrador
 * 
 * Maneja el panel de administración y la creación de nuevos alojamientos
 */

require_once __DIR__ . '/../Models/Accommodation.php';
require_once __DIR__ . '/AuthController.php';

class AdminController {
    
    /**
     * Modelo de alojamiento
     * @var Accommodation
     */
    private $accommodationModel;
    
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
        $this->auth = new AuthController();
    }
    
    /**
     * Muestra el panel de administración
     */
    public function index() {
        // Verificar que sea administrador
        $this->auth->requiereAdmin();
        
        // Obtener todos los alojamientos
        $alojamientos = $this->accommodationModel->obtenerTodos();
        
        // Obtener estadísticas
        $estadisticas = $this->accommodationModel->obtenerEstadisticas();
        
        require_once __DIR__ . '/../Views/admin/index.php';
    }
    
    /**
     * Muestra el formulario para agregar un nuevo alojamiento
     */
    public function agregarAlojamiento() {
        // Verificar que sea administrador
        $this->auth->requiereAdmin();
        
        require_once __DIR__ . '/../Views/admin/agregar-alojamiento.php';
    }
    
    /**
     * Procesa la creación de un nuevo alojamiento
     */
    public function procesarAgregarAlojamiento() {
        // Verificar que sea administrador
        $this->auth->requiereAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/admin/agregar-alojamiento');
            return;
        }
        
        // Obtener y sanitizar datos del formulario
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $imagePath = trim($_POST['image_path'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $pricePerNight = floatval($_POST['price_per_night'] ?? 0);
        $rating = floatval($_POST['rating'] ?? 0);
        
        // Validaciones
        $errores = [];
        
        if (empty($name)) {
            $errores[] = "El nombre es requerido";
        }
        
        if (empty($description)) {
            $errores[] = "La descripción es requerida";
        }
        
        if (empty($imagePath)) {
            $errores[] = "La URL de la imagen es requerida";
        } elseif (!filter_var($imagePath, FILTER_VALIDATE_URL)) {
            $errores[] = "La URL de la imagen no es válida";
        }
        
        if (empty($location)) {
            $errores[] = "La ubicación es requerida";
        }
        
        if ($pricePerNight <= 0) {
            $errores[] = "El precio debe ser mayor a 0";
        }
        
        if ($rating < 0 || $rating > 5) {
            $errores[] = "La calificación debe estar entre 0 y 5";
        }
        
        // Si hay errores, regresar al formulario
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['old_input'] = [
                'name' => $name,
                'description' => $description,
                'image_path' => $imagePath,
                'location' => $location,
                'price_per_night' => $pricePerNight,
                'rating' => $rating
            ];
            $this->redirigir('/admin/agregar-alojamiento');
            return;
        }
        
        // Crear alojamiento
        if ($this->accommodationModel->crear($name, $description, $imagePath, $location, $pricePerNight, $rating)) {
            $_SESSION['success'] = "Alojamiento agregado exitosamente";
            $this->redirigir('/admin');
        } else {
            $_SESSION['errores'] = ["Error al agregar el alojamiento"];
            $this->redirigir('/admin/agregar-alojamiento');
        }
    }
    
    /**
     * Muestra las estadísticas del sistema
     */
    public function estadisticas() {
        // Verificar que sea administrador
        $this->auth->requiereAdmin();
        
        // Obtener estadísticas
        $estadisticas = $this->accommodationModel->obtenerEstadisticas();
        $alojamientosMejorValorados = $this->accommodationModel->obtenerMejorValorados(5);
        $alojamientosRecientes = $this->accommodationModel->obtenerRecientes(5);
        
        require_once __DIR__ . '/../Views/admin/estadisticas.php';
    }
    
    /**
     * Muestra el formulario para editar un alojamiento
     */
    public function editarAlojamiento() {
        // Verificar que sea administrador
        $this->auth->requiereAdmin();
        
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['errores'] = ["ID de alojamiento no especificado"];
            $this->redirigir('/admin');
            return;
        }
        
        $alojamiento = $this->accommodationModel->buscarPorId($id);
        
        if (!$alojamiento) {
            $_SESSION['errores'] = ["Alojamiento no encontrado"];
            $this->redirigir('/admin');
            return;
        }
        
        require_once __DIR__ . '/../Views/admin/editar-alojamiento.php';
    }
    
    /**
     * Procesa la actualización de un alojamiento
     */
    public function procesarEditarAlojamiento() {
        // Verificar que sea administrador
        $this->auth->requiereAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/admin');
            return;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['errores'] = ["ID de alojamiento no especificado"];
            $this->redirigir('/admin');
            return;
        }
        
        // Obtener y sanitizar datos del formulario
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $imagePath = trim($_POST['image_path'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $pricePerNight = floatval($_POST['price_per_night'] ?? 0);
        $rating = floatval($_POST['rating'] ?? 0);
        
        // Validaciones
        $errores = [];
        
        if (empty($name)) {
            $errores[] = "El nombre es requerido";
        }
        
        if (empty($description)) {
            $errores[] = "La descripción es requerida";
        }
        
        if (empty($imagePath)) {
            $errores[] = "La URL de la imagen es requerida";
        } elseif (!filter_var($imagePath, FILTER_VALIDATE_URL)) {
            $errores[] = "La URL de la imagen no es válida";
        }
        
        if (empty($location)) {
            $errores[] = "La ubicación es requerida";
        }
        
        if ($pricePerNight <= 0) {
            $errores[] = "El precio debe ser mayor a 0";
        }
        
        if ($rating < 0 || $rating > 5) {
            $errores[] = "La calificación debe estar entre 0 y 5";
        }
        
        // Si hay errores, regresar al formulario
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['old_input'] = [
                'id' => $id,
                'name' => $name,
                'description' => $description,
                'image_path' => $imagePath,
                'location' => $location,
                'price_per_night' => $pricePerNight,
                'rating' => $rating
            ];
            $this->redirigir('/admin/editar-alojamiento?id=' . $id);
            return;
        }
        
        // Actualizar alojamiento
        if ($this->accommodationModel->actualizar($id, $name, $description, $imagePath, $location, $pricePerNight, $rating)) {
            $_SESSION['success'] = "Alojamiento actualizado exitosamente";
            $this->redirigir('/admin');
        } else {
            $_SESSION['errores'] = ["Error al actualizar el alojamiento"];
            $this->redirigir('/admin/editar-alojamiento?id=' . $id);
        }
    }
    
    /**
     * Elimina un alojamiento
     */
    public function eliminarAlojamiento() {
        // Verificar que sea administrador
        $this->auth->requiereAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/admin');
            return;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['errores'] = ["ID de alojamiento no especificado"];
            $this->redirigir('/admin');
            return;
        }
        
        // Verificar que existe
        $alojamiento = $this->accommodationModel->buscarPorId($id);
        
        if (!$alojamiento) {
            $_SESSION['errores'] = ["Alojamiento no encontrado"];
            $this->redirigir('/admin');
            return;
        }
        
        // Eliminar alojamiento
        if ($this->accommodationModel->eliminar($id)) {
            $_SESSION['success'] = "Alojamiento eliminado exitosamente";
        } else {
            $_SESSION['errores'] = ["Error al eliminar el alojamiento"];
        }
        
        $this->redirigir('/admin');
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
