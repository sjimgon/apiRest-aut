<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../db.class.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Función para verificar si el token es válido
function isAuthenticated() {
    // Clave secreta para la decodificación
    $secret_key = "claveSecreta";
    // Obtener el token del encabezado
    $token = $_SERVER['HTTP_API_KEY'];

    if (!$token) {
        http_response_code(401);

        echo json_encode(["message" => "Token no proporcionado"]);
        exit();
    }

    try {
        // Decodificamos el token a través de los métodos de la librería Firebase JWT
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));

        //Creamos la conexión para verificar si el token es válido
        $db = new DB();
        $conn = $db->getConnection();
        
        $user_id = $decoded->id;

        // Obtenemos el user del token de la URL, si token almacenado y el de URL coinciden, entonces el token es válido
        $query = "SELECT token FROM users WHERE id = $user_id";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && $user['token'] === $token) {
            // El token es válido
            echo json_encode(["message" => "Acceso permitido"]);
            return true;
        } else {
            // Token no coincide o no está almacenado en la base de datos
            http_response_code(401);
            echo json_encode(["message" => "Acceso denegado. Token no válido"]);
            return false;
        }
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["message" => "Token no válido", "error" => $e->getMessage()]);
        return false;
    }
}
?>
