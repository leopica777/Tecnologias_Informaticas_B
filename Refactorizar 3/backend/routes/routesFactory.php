<?php
/**
*    File        : backend/routes/routesFactory.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

/*Este archivo define una función reutilizable llamada
routeRequest(...) que se encarga de gestionar las rutas REST
(GET, POST, PUT, DELETE) y de conectar cada método HTTP con la
función que debe ejecutarse.*/


function routeRequest($conn, $customHandlers = [], $prefix = 'handle') //Funcion routeRequest. $customhandlers array de funciones personalizadas para ciertos metodos http. $prefix prefijo para construir los nombres por defecto de los handlers
{
    $method = $_SERVER['REQUEST_METHOD'];//Detecta el metodo HTTP de la peticion

    // Lista de handlers CRUD por defecto. Define funciones por defecto
    $defaultHandlers = [
        'GET'    => $prefix . 'Get',
        'POST'   => $prefix . 'Post',
        'PUT'    => $prefix . 'Put',
        'DELETE' => $prefix . 'Delete'
    ]; //HandleGet. HandlePost. Handleput. HandleDelete

    // Sobrescribir handlers por defecto si hay personalizados
    $handlers = array_merge($defaultHandlers, $customHandlers);

    //Valida si el metodo es soportado
    if (!isset($handlers[$method])) 
    {
        http_response_code(405);
        echo json_encode(["error" => "Método $method no permitido"]);
        return;
    }

    //Ejecuta handler correspondiente
    $handler = $handlers[$method];//guarda el nombre de la funcion

    if (is_callable($handler)) //si puede ser llamado como funcion
    {
        $handler($conn);
    }
    else
    {
        http_response_code(500);
        echo json_encode(["error" => "Handler para $method no es válido"]);
    }
}
