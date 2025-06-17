<?php
/**
*    File        : backend/routes/studentsRoutes.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

/*Este archivo se encarga de manejar las rutas específicas del
módulo students. Actúa como un "puente" entre la solicitud que
llega del frontend y el controlador que se encarga de
procesarla.*/

//Cargar y ejecutar archivos necesarios
require_once("./config/databaseConfig.php");//contiene los datos para conectarse a la base
require_once("./routes/routesFactory.php");//contiene la funcion routerequest
require_once("./controllers/studentsController.php");//contiene funciones handle

// routeRequest($conn);


/**
 * Ejemplo de como se extiende un archivo de rutas 
 * para casos particulares
 * o validaciones:
 */

//Extensión personalizada de la ruta POST
routeRequest($conn, [ //usa routerequest para definir ruta personalizada para solicitudes POST
    'POST' => function($conn) //Define lo que debe pasar si el metodo HTTP es Post
    {
        // Validación o lógica extendida
        $input = json_decode(file_get_contents("php://input"), true);//Lee el cuerpo del mensaje http en json, lo convierte en un array de PHP.
        if (empty($input['fullname'])) //si el campo fullname no esta rellenado
        {
            http_response_code(400);//error
            echo json_encode(["error" => "Falta el nombre"]);
            return;
        }
        handlePost($conn);//si esta todo bien. se guarda el estudiante en la base de datos.
    }
]);
