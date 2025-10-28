<?php
/**
 * Configuración de la aplicación (valores simples)
 * Devuelve un arreglo con configuraciones útiles como la URL base.
 */

// Calcular BASE_URL dinámicamente a partir del script name
$scriptName = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
$baseUrl = rtrim($scriptName, '/');

// Normalizar: si quedó vacío, dejar cadena vacía (raíz)
if ($baseUrl === '/') {
    $baseUrl = '';
}

return [
    'base_url' => $baseUrl,
];
