<?php
require_once 'db.class.php';

if(isset($_POST['user']) && isset($_POST['password']) && isset($_POST)){
  
  $user = $_POST['user'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$db = new DB();
$conn = $db->getConnection();

$query = "INSERT INTO users (username, password) VALUES ('$user', '$password')";
$stmt = $conn->prepare($query);
$stmt->execute();
echo "Usuario registrado correctamente";

header('Location: login.php');

}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
  <h1>Registro</h1>
    <form action="registro.php" method="POST">
        <label for="user">Usuario</label>
        <input type="text" name="user" id="user" required>
        <label for="brand">Contraseña</label>
        <input type="text" name="password" id="password" required>
        <button type="submit">Registrar</button>  
</body>
</html>