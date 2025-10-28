<?php
/**
 * Controlador de Autenticación
 * 
 * Maneja el registro, login y logout de usuarios
 */

require_once __DIR__ . '/../Models/User.php';

class AuthController {
    
    /**
     * Modelo de usuario
     * @var User
     */
    private $userModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->userModel = new User();
        
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Muestra el formulario de registro
     */
    public function mostrarRegistro() {
        // Si ya está autenticado, redirigir al panel
        if ($this->estaAutenticado()) {
            $this->redirigir($this->esAdmin() ? '/admin' : '/mi-cuenta');
            return;
        }
        
        require_once __DIR__ . '/../Views/auth/registro.php';
    }
    
    /**
     * Procesa el registro de un nuevo usuario
     */
    public function procesarRegistro() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/registro');
            return;
        }
        
        // Obtener y sanitizar datos del formulario
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        
        // Validaciones
        $errores = [];
        
        if (empty($name)) {
            $errores[] = "El nombre es requerido";
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El email no es válido";
        }
        
        if (strlen($password) < 6) {
            $errores[] = "La contraseña debe tener al menos 6 caracteres";
        }
        
        if ($password !== $passwordConfirm) {
            $errores[] = "Las contraseñas no coinciden";
        }
        
        if ($this->userModel->emailExiste($email)) {
            $errores[] = "Este email ya está registrado";
        }
        
        // Si hay errores, mostrar formulario con errores
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['old_input'] = ['name' => $name, 'email' => $email];
            $this->redirigir('/registro');
            return;
        }
        
        // Crear usuario
        if ($this->userModel->crear($name, $email, $password)) {
            $_SESSION['success'] = "Registro exitoso. Por favor, inicia sesión.";
            $this->redirigir('/login');
        } else {
            $_SESSION['errores'] = ["Error al crear la cuenta. Por favor, intenta nuevamente."];
            $this->redirigir('/registro');
        }
    }
    
    /**
     * Muestra el formulario de login
     */
    public function mostrarLogin() {
        // Si ya está autenticado, redirigir al panel
        if ($this->estaAutenticado()) {
            $this->redirigir($this->esAdmin() ? '/admin' : '/mi-cuenta');
            return;
        }
        
        require_once __DIR__ . '/../Views/auth/login.php';
    }
    
    /**
     * Procesa el inicio de sesión
     */
    public function procesarLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/login');
            return;
        }
        
        // Obtener credenciales
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Validar credenciales
        $usuario = $this->userModel->verificarCredenciales($email, $password);
        
        if ($usuario) {
            // Guardar datos en sesión
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_name'] = $usuario['name'];
            $_SESSION['user_email'] = $usuario['email'];
            $_SESSION['user_role'] = $usuario['role'];
            
            // Redirigir según el rol
            if ($usuario['role'] === 'admin') {
                $this->redirigir('/admin');
            } else {
                $this->redirigir('/mi-cuenta');
            }
        } else {
            $_SESSION['errores'] = ["Credenciales incorrectas"];
            $_SESSION['old_input'] = ['email' => $email];
            $this->redirigir('/login');
        }
    }
    
    /**
     * Cierra la sesión del usuario
     */
    public function logout() {
        // Destruir todas las variables de sesión
        $_SESSION = [];
        
        // Destruir la cookie de sesión
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        // Destruir la sesión
        session_destroy();
        
        // Redirigir a la página principal
        $this->redirigir('/');
    }
    
    /**
     * Verifica si el usuario está autenticado
     * 
     * @return bool
     */
    public function estaAutenticado() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Verifica si el usuario es administrador
     * 
     * @return bool
     */
    public function esAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
    
    /**
     * Requiere que el usuario esté autenticado
     * Redirige al login si no lo está
     */
    public function requiereAuth() {
        if (!$this->estaAutenticado()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            $this->redirigir('/login');
            exit;
        }
    }
    
    /**
     * Requiere que el usuario sea administrador
     * Redirige si no tiene permisos
     */
    public function requiereAdmin() {
        $this->requiereAuth();
        
        if (!$this->esAdmin()) {
            $_SESSION['errores'] = ["No tienes permisos para acceder a esta sección"];
            $this->redirigir('/mi-cuenta');
            exit;
        }
    }
    
    /**
     * Obtiene el ID del usuario actual
     * 
     * @return int|null
     */
    public function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Obtiene el nombre del usuario actual
     * 
     * @return string|null
     */
    public function getUserName() {
        return $_SESSION['user_name'] ?? null;
    }
    
    /**
     * Redirige a una URL
     * 
     * @param string $url URL de destino
     */
    private function redirigir($url) {
        // Si la URL es completa (http://...) no la modificamos
        if (preg_match('/^https?:\/\//', $url)) {
            header("Location: $url");
            exit;
        }

        // Si la URL empieza con '/', anteponer BASE_URL si está definido
        if (strpos($url, '/') === 0) {
            $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
            $url = $base === '' ? $url : $base . $url;
        }

        header("Location: $url");
        exit;
    }
}
