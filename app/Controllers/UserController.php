<?php
/**
 * Controlador de Usuario
 * 
 * Maneja el panel de cuenta del usuario y sus favoritos
 */

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/AuthController.php';

class UserController {
    
    /**
     * Modelo de usuario
     * @var User
     */
    private $userModel;
    
    /**
     * Controlador de autenticación
     * @var AuthController
     */
    private $auth;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->userModel = new User();
        $this->auth = new AuthController();
    }
    
    /**
     * Muestra el panel de cuenta del usuario con sus favoritos
     */
    public function miCuenta() {
        // Verificar autenticación
        $this->auth->requiereAuth();
        
        // Obtener datos del usuario
        $usuario = $this->userModel->buscarPorId($this->auth->getUserId());
        
        // Obtener alojamientos favoritos
        $favoritos = $this->userModel->obtenerFavoritos($this->auth->getUserId());
        
        // Mostrar vista
        require_once __DIR__ . '/../Views/user/mi-cuenta.php';
    }
    
    /**
     * Muestra el formulario de edición de perfil
     */
    public function editarPerfil() {
        // Verificar autenticación
        $this->auth->requiereAuth();
        
        // Obtener datos del usuario
        $usuario = $this->userModel->buscarPorId($this->auth->getUserId());
        
        require_once __DIR__ . '/../Views/user/editar-perfil.php';
    }
    
    /**
     * Procesa la actualización del perfil
     */
    public function actualizarPerfil() {
        // Verificar autenticación
        $this->auth->requiereAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/mi-cuenta');
            return;
        }
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        
        // Validaciones
        $errores = [];
        
        if (empty($name)) {
            $errores[] = "El nombre es requerido";
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El email no es válido";
        }
        
        // Verificar si el email ya existe (excepto el del usuario actual)
        $usuarioConEmail = $this->userModel->buscarPorEmail($email);
        if ($usuarioConEmail && $usuarioConEmail['id'] != $this->auth->getUserId()) {
            $errores[] = "Este email ya está en uso por otro usuario";
        }
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $this->redirigir('/editar-perfil');
            return;
        }
        
        // Actualizar perfil
        if ($this->userModel->actualizar($this->auth->getUserId(), $name, $email)) {
            // Actualizar datos en sesión
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            
            $_SESSION['success'] = "Perfil actualizado exitosamente";
        } else {
            $_SESSION['errores'] = ["Error al actualizar el perfil"];
        }
        
        $this->redirigir('/mi-cuenta');
    }
    
    /**
     * Muestra el formulario para cambiar contraseña
     */
    public function cambiarPassword() {
        // Verificar autenticación
        $this->auth->requiereAuth();
        
        require_once __DIR__ . '/../Views/user/cambiar-password.php';
    }
    
    /**
     * Procesa el cambio de contraseña
     */
    public function procesarCambioPassword() {
        // Verificar autenticación
        $this->auth->requiereAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/cambiar-password');
            return;
        }
        
        $passwordActual = $_POST['password_actual'] ?? '';
        $passwordNueva = $_POST['password_nueva'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        
        // Validaciones
        $errores = [];
        
        // Obtener usuario actual
        $usuario = $this->userModel->buscarPorId($this->auth->getUserId());
        
        // Verificar contraseña actual
        if (!password_verify($passwordActual, $usuario['password'])) {
            $errores[] = "La contraseña actual es incorrecta";
        }
        
        if (strlen($passwordNueva) < 6) {
            $errores[] = "La nueva contraseña debe tener al menos 6 caracteres";
        }
        
        if ($passwordNueva !== $passwordConfirm) {
            $errores[] = "Las contraseñas no coinciden";
        }
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $this->redirigir('/cambiar-password');
            return;
        }
        
        // Cambiar contraseña
        if ($this->userModel->cambiarPassword($this->auth->getUserId(), $passwordNueva)) {
            $_SESSION['success'] = "Contraseña cambiada exitosamente";
            $this->redirigir('/mi-cuenta');
        } else {
            $_SESSION['errores'] = ["Error al cambiar la contraseña"];
            $this->redirigir('/cambiar-password');
        }
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
