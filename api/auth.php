<?php
require_once '../vendor/autoload.php';
require_once '../db.class.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth{

    private $secret_key;

    public function isAuthenticated() {
        // Clave secreta
        $this->secret_key = "claveSecreta";
    }

    public function generarToken($username, $password) {

        // Crear conexión a la base de datos
        $db = new DB();
        $conn = $db->getConnection();

        // Seleccionamos el usuario de la base de datos
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Si existe el usuario, creamos el token y lo guardamos en la base de datos
        if ($user) {

                // Generamos el payload
                $payload = [
                        "id" => $user['id'],
                        "username" => $user['username']
                ];
                
                // Generar el token
                $jwt = JWT::encode($payload, $this->secret_key, 'HS256');

                // Guardar el token en la base de datos
                $query = "UPDATE users SET token = '$jwt' WHERE id = " . $user['id'];
                $stmt = $conn->prepare($query);
                $stmt->execute();

                return $jwt;

            } else {
                http_response_code(401);
                echo json_encode(["message" => "Usuario o contraseña incorrectos"]);
            }

    }
}
?>
