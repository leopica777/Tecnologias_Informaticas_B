<?php
//StudentsRoutes.php
//Conecta a la base de datos
//Incluye el controlador que maneja la logica del CRUD
//Delega peticiones al controlador segun el metodo HTTP usado

require_once("./config/databaseConfig.php"); //Incluye config
require_once("./controllers/studentsController.php");//Incluye controller

switch ($_SERVER['REQUEST_METHOD']) { //se obtiene el tipo de solicitud HTTP que el cliente hace
    case 'GET':
        handleGet($conn); //ejecuta la funcion handleget que esta en el controller y le pasa los datos de conexion a la BD
        break;
    case 'POST':
        handlePost($conn);
        break;
    case 'PUT':
        handlePut($conn);
        break;
    case 'DELETE':
        handleDelete($conn);
        break;
    default: //en caso de utilizar otro metodo, este no es contemplado
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]); //mensaje de error en json
        break;
}
?>
