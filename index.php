<?php
require_once 'db.class.php';
require_once 'ModeloController.php';
require_once 'api.REST.class.php';
//apiREST.class.php será una clase que se encargará de manejar las peticiones REST y devolver las respuestas
//Será una clase genérica para reusar con distintas tablas de la base de datos
//Por ello, se le pasará el controlador de la tabla que se quiera manejar y el nombre de la tabla
//para que maneje el endpoint(url) correspondiente basado en ese nombre.

// Configuración de la conexión a la base de datos (deberías ajustar esto según tu configuración)

$servername = "";
$username = "";
$password = "";
$dbname = "";

$db = new DB($servername, $username, $password, $dbname);

$modeloController = new ModeloController($db);


$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);
$endpoint = $request_uri[1];
$MiApi = new apiREST($modeloController, 'modelo');
$MiApi->action($endpoint);


?>
