<?php
require_once '../vendor/autoload.php';
require_once '../db.class.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Clave secreta
$secret_key = "claveSecreta";

// Verificar si los datos de la solicitud están presentes
if (!isset($_POST['username']) || !isset($_POST['password'])) {
    http_response_code(400);
    echo json_encode(["message" => "Faltan parámetros en la solicitud"]);
    exit();
}

// Datos de la solicitud (username y password)
$data = array_values($_POST);
$username = $data[0];
$password = $data[1];

// Crear conexión a la base de datos
$db = new DB();
$conn = $db->getConnection();

// Comprobamos si el usuario existe en la base de datos
$query = "SELECT * FROM users WHERE username = '$username'"; // Concatenación directa de la consulta
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Si existe el usuario, comprobamos si la contraseña es correcta
if ($user) {
    if (hash('sha256', $password) === $user['password']) {
        // Si la contraseña es correcta generamos el token
        $payload = [
                "id" => $user['id'],
                "username" => $user['username']
        ];
        
        // Generar el token
        $jwt = JWT::encode($payload, $secret_key, 'HS256');

        // Guardar el token en la base de datos
        $query = "UPDATE users SET token = '$jwt' WHERE id = " . $user['id'];
        $stmt = $conn->prepare($query);
        $stmt->execute();

        
        // Devolver el token al cliente
        header('Content-Type: application/json');
        echo json_encode([
            "message" => "Token generado exitosamente",
            "token" => $jwt
        ]);
    } else {
        // Contraseña incorrecta
        http_response_code(401);
        echo json_encode(["message" => "Contraseña incorrecta"]);
    }
} else {
    // Usuario no encontrado
    http_response_code(404);
    echo json_encode(["message" => "Usuario no encontrado"]);
}
?>
