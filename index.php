<?php
/**
 * AlojaTEC - Punto de Entrada Principal
 * 
 * Este archivo actúa como el Front Controller de la aplicación,
 * manejando todas las solicitudes y enrutándolas a los controladores correspondientes.
 */

// Iniciar sesión
session_start();

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
if (isset($routes[$requestUri])) {
    $route = $routes[$requestUri];
    $controllerName = $route['controller'];
    $methodName = $route['method'];
    
    // Instanciar el controlador y llamar al método
    try {
        $controller = new $controllerName();
        $controller->$methodName();
    } catch (Exception $e) {
        // Log del error
        error_log("Error en ruta $requestUri: " . $e->getMessage());
        
        // Mostrar página de error
        http_response_code(500);
        echo "Error interno del servidor. Por favor, intenta más tarde.";
    }
} else {
    // Ruta no encontrada - 404
    http_response_code(404);
    require_once __DIR__ . '/app/Views/errors/404.php';
}
