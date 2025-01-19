<?php
require_once 'db.class.php';

if(isset($_POST['user']) && isset($_POST['password'])){
    $user = $_POST['user'];
    if(password_verify($_POST['password'], PASSWORD_DEFAULT)){
        $password = $_POST['password'];
    } else {
        echo "Contraseña incorrecta";
    }

    $db = new DB();
    $conn = $db->getConnection();

    $query = "SELECT * FROM users WHERE username = '$user' AND password = '$password'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        
        session_start();
        $_SESSION['user'] = $user;
        $_SESSION['password'] = $password;

        header('Location: generarToken.php');
    } else {
        echo "Usuario o contraseña incorrectos";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
</head>
<body>
    <h1>Iniciar sesión</h1>
    <form action="login.php" method="POST">
        <label for="user">Usuario</label>
        <input type="text" name="user" id="user" required>
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Iniciar sesión</button>
</body>
</html>