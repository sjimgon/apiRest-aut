<?php
require_once 'db.class.php';
require_once './api/auth.php';

if(isset($_POST)){
    $db = new DB();
    $conn = $db->getConnection();
    $auth = new Auth();
    $token = $auth->generarToken($_SESSION['user'], $_SESSION['password']);
    echo $token;
    echo "Token generado correctamente";
    session_destroy();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generación de token</title>
</head>
<body>
    <form action="" method="post">
        <button type="submit">Generar token</button>
    </form>
</body>
</html>