<?php
/**
 * AlojaTEC - Punto de Entrada Principal
 * 
 * Este archivo actúa como el Front Controller de la aplicación,
 * manejando todas las solicitudes y enrutándolas a los controladores correspondientes.
 */

// Iniciar sesión
session_start();

// Cargar configuración de la aplicación (BASE_URL, etc.)
$appConfig = require_once __DIR__ . '/config/app.php';
// Definir constante para ser usada en vistas
define('BASE_URL', $appConfig['base_url'] ?? '');

// Autoload de clases
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/app/',
        __DIR__ . '/app/Controllers/',
        __DIR__ . '/app/Models/',
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Obtener la URI solicitada
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Eliminar el directorio base si la aplicación no está en la raíz
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
if ($scriptName !== '/') {
    $requestUri = str_replace($scriptName, '', $requestUri);
}

// Eliminar barra final si existe
$requestUri = rtrim($requestUri, '/');
if (empty($requestUri)) {
    $requestUri = '/';
}

// Definir rutas
$routes = [
    // Rutas públicas
    '/' => ['controller' => 'AccommodationController', 'method' => 'index'],
    '/buscar' => ['controller' => 'AccommodationController', 'method' => 'buscar'],
    
    // Rutas de autenticación
    '/login' => ['controller' => 'AuthController', 'method' => 'mostrarLogin'],
    '/login-post' => ['controller' => 'AuthController', 'method' => 'procesarLogin'],
    '/registro' => ['controller' => 'AuthController', 'method' => 'mostrarRegistro'],
    '/registro-post' => ['controller' => 'AuthController', 'method' => 'procesarRegistro'],
    '/logout' => ['controller' => 'AuthController', 'method' => 'logout'],
    
    // Rutas de usuario
    '/mi-cuenta' => ['controller' => 'UserController', 'method' => 'miCuenta'],
    '/editar-perfil' => ['controller' => 'UserController', 'method' => 'editarPerfil'],
    '/actualizar-perfil' => ['controller' => 'UserController', 'method' => 'actualizarPerfil'],
    '/cambiar-password' => ['controller' => 'UserController', 'method' => 'cambiarPassword'],
    '/procesar-cambio-password' => ['controller' => 'UserController', 'method' => 'procesarCambioPassword'],
    
    // Rutas de favoritos
    '/agregar-favorito' => ['controller' => 'AccommodationController', 'method' => 'agregarFavorito'],
    '/eliminar-favorito' => ['controller' => 'AccommodationController', 'method' => 'eliminarFavorito'],
    
    // Rutas de reseñas
    '/agregar-resena' => ['controller' => 'AccommodationController', 'method' => 'agregarReseña'],
    
    // Rutas de administrador
    '/admin' => ['controller' => 'AdminController', 'method' => 'index'],
    '/admin/agregar-alojamiento' => ['controller' => 'AdminController', 'method' => 'agregarAlojamiento'],
    '/admin/procesar-agregar' => ['controller' => 'AdminController', 'method' => 'procesarAgregarAlojamiento'],
    '/admin/editar-alojamiento' => ['controller' => 'AdminController', 'method' => 'editarAlojamiento'],
    '/admin/procesar-editar' => ['controller' => 'AdminController', 'method' => 'procesarEditarAlojamiento'],
    '/admin/eliminar-alojamiento' => ['controller' => 'AdminController', 'method' => 'eliminarAlojamiento'],
    '/admin/estadisticas' => ['controller' => 'AdminController', 'method' => 'estadisticas'],
];

// Verificar si la ruta existe
// Verificar si la ruta existe
if (isset($routes[$requestUri])) {
    $route = $routes[$requestUri];
    $controllerName = $route['controller'];
    $methodName = $route['method'];

    // Verificar existencia de la clase y el método antes de instanciar
    if (!class_exists($controllerName)) {
        error_log("Controlador no encontrado: $controllerName para la ruta $requestUri");
        http_response_code(500);
        require_once __DIR__ . '/app/Views/errors/500.php';
        exit;
    }

    // Instanciar el controlador
    $controller = new $controllerName();

    if (!method_exists($controller, $methodName)) {
        error_log("Método no encontrado: $controllerName::$methodName para la ruta $requestUri");
        http_response_code(500);
        require_once __DIR__ . '/app/Views/errors/500.php';
        exit;
    }

    // Llamar al método y manejar excepciones internas
    try {
        $controller->$methodName();
    } catch (Throwable $e) {
        // Registrar y mostrar página de error 500
        error_log("Excepción al ejecutar $controllerName::$methodName - " . $e->getMessage());
        http_response_code(500);
        require_once __DIR__ . '/app/Views/errors/500.php';
    }
} else {
    // Ruta no encontrada - 404
    http_response_code(404);
    require_once __DIR__ . '/app/Views/errors/404.php';
}
