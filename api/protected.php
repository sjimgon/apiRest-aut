<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Función para verificar si el token es válido
function isAuthenticated() {
    // Clave secreta para la decodificación
    $secret_key = "claveSecreta";
    // Obtener el token del encabezado
    $token = $_SERVER['HTTP_API_KEY'??null];

    if (!$token) {
        return false;  // No hay token
    }

    try {
        // Decodificar el token con la clave secreta
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        return true;  // El token es válido
    } catch (Exception $e) {
        return false;  // El token no es válido
    }
}
?>
