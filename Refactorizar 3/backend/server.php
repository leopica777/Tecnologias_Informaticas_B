<?php
/**

DOCUMENTACION
*    File        : backend/server.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/


//MOSTRAR ERRORES, SOLO PARA EL DESARROLLADOR
/**FOR DEBUG: */
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


/* Su función es recibir las peticiones del frontend, verificar qué módulo se solicita (como students),
y luego redirigir la ejecución al archivo de rutas correspondiente para procesar la acción (listar, crear, actualizar o borrar datos).
1. Recibe la solicitud del navegador o frontend.
2. Valida si el módulo es correcto.
3. Redirige la petición al archivo PHP adecuado según el
módulo.
4. Maneja solicitudes OPTIONS automáticamente para CORS.
5. Si algo falla, responde con un mensaje de error en JSON.
*/

//Configuracion de cabeceras HTTP
header("Access-Control-Allow-Origin: *"); //Permite que cualquier sitio web se comunique con este servidor.
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); //Indica que metodos HTTP se permiten
header("Access-Control-Allow-Headers: Content-Type"); //Permite que las solicitudes incluyan el tipo de contenido.

//Función auxiliar para responder con código HTTP
function sendCodeMessage($code, $message = "")
{
    http_response_code($code);//Envia codigo de estado HTTP
    echo json_encode(["message" => $message]); //Convierte un mensaje PHP en texto JSON para que lo entienda el navegador
    exit();// Detiene el script
}

// Respuesta correcta para solicitudes OPTIONS (preflight) Manejo de solicitudes HTTP tipo OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS')
{
    sendCodeMessage(200); // 200 OK
}

// Obtener el módulo desde la query string URL
$uri = parse_url($_SERVER['REQUEST_URI']); //contiene la ruta completa que pidio el navegador
$query = $uri['query'] ?? ''; 
parse_str($query, $query_array);//convierte el string en un array asociativo
$module = $query_array['module'] ?? null; //tendra el valor de students

// Validación de existencia del módulo
if (!$module)
{
    sendCodeMessage(400, "Módulo no especificado");
}

// Validación de caracteres seguros en el modulo: solo letras, números y guiones bajos
if (!preg_match('/^\w+$/', $module))
{
    sendCodeMessage(400, "Nombre de módulo inválido");
}

// Buscar el archivo de ruta correspondiente
$routeFile = __DIR__ . "/routes/{$module}Routes.php";

if (file_exists($routeFile))
{
    require_once($routeFile);//si el archivo existe, se carga y ejecuta 
}
else
{
    sendCodeMessage(404, "Ruta para el módulo '{$module}' no encontrada");//error 404 not found
}
