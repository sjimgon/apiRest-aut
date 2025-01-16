<?php
/* Clase genérica que manejará las peticiones REST y devolverá las respuestas */
require_once './api/protected.php';
class apiRest{
    private $controller;
    private $url;

    public function __construct($controller, $url){
        $this->controller = $controller;
        $this->url = $url;
    }

    public function action($endpoint){
        $arrayUri = explode('/', $endpoint);//Dividimos el endopoint de la URL para averiguar que
                                            //accion realiza y en base a que id             Formto: (entrypoint)(/modelo/1)<-Endpoint
        $method = $_SERVER['REQUEST_METHOD'];//Tenemos que tener en cuenta que método se está utilizando
        $response = null;//Aquí guardaremos la respuesta que devolveremos al cliente segun el protocolo(JSON)

        //Función   Verbo    URL
        //INDEX     GET      arrayUri[0] /                              //Obtener todos los registros
        //SHOW      GET      arrayUri[0] / id(arrayUri[1])              //Obtener un registro por id
        //STORE     POST     arrayUri[0] / store                        //Formulario para crear un registro
        //UPDATE    PUT      arrayUri[0] / update / id(arrayUri[2])     //Actualizar un registro con los datos procedentes de un formulario -> No es posible mediante un formulario, utilizaremos POST
        //DELETE    DELETE   arrayUri[0] / destroy / id(arrayUri[2])    //Eliminar un registro -> No es posible mediante un formulario, utilizaremos POST
        
        switch ($method) {
            case 'GET':
                if ($arrayUri[0] == $this->url) { // Comprueba si el primer fragmento del endpoint es igual a la URL base
                    /*INDEX*/
                    if (count($arrayUri) == 1) {
                        try {
                            $response = $this->controller->getAll();
                            echo json_encode($response);
                        } catch (Exception $e) {
                            http_response_code(404);
                        }
                    /*SHOW*/
                    } elseif (count($arrayUri) == 2 && $arrayUri[1] != "store") {
                        try {
                            $response = $this->controller->getById($arrayUri[1]);
                            if ($response == null) {
                                http_response_code(404);
                                echo "Registro no encontrado";
                            } else {
                                echo json_encode($response);
                            }
                        } catch (Exception $e) {
                            http_response_code(404);
                        }
                    }
                }
                break;
        
            case 'POST':
                /*STORE*/
                if ($arrayUri[0] == $this->url) {
                    if ($arrayUri[1] == "store") {
                        try {
                            $data = array_values($_POST);
                            // creamos el Modelo a partir del $data -> id,name,brand,year
                            $this->controller->createModelo($data[1], $data[2], $data[3]);
                            echo "Registro creado correctamente";
                        } catch (Exception $e) {
                            echo "Error al crear el registro";
                        }
                    /*DESTROY*/
                    } elseif ($arrayUri[1] == "destroy") {

                        if (count($arrayUri) == 2) {
                            echo "Introduce el id del registro a eliminar";
                        } elseif (count($arrayUri) == 3) {
                            // Hacemos la comprobación de autenticación antes de eliminar el registro
                            if (!isAuthenticated()) {
                                http_response_code(401);
                                echo json_encode(["message" => "Acceso denegado. Token no válido o ausente"]);
                                exit();
                            }
                            try {
                                $this->controller->delete($arrayUri[2]);
                                echo "Registro eliminado";
                            } catch (Exception $e) {
                                http_response_code(404);
                                echo "Error al eliminar el registro";
                            }
                        }
                    /*UPDATE*/
                    } elseif ($arrayUri[1] == "update") {
                        if (count($arrayUri) == 2) {
                            echo "Introduce el id del registro para actualizar";
                        } elseif (count($arrayUri) == 3) {
                            // Comprobamos la autenticación antes de actualizar el registro
                            if (!isAuthenticated()) {
                                http_response_code(401);
                                echo json_encode(["message" => "Acceso denegado. Token no válido o ausente"]);
                                exit();
                            }
                            try {
                                $data = array_values($_POST);
                                // $data -> id,name,brand,year
                                $this->controller->updateModelo($arrayUri[2], $data[1], $data[2], $data[3]);
                                echo "Registro actualizado correctamente";
                            } catch (Exception $e) {
                                echo "Error al actualizar el registro";
                                http_response_code(404);
                            }
                        }
                    }
                }
                break;
        }
    }
    }

?>