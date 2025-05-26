<?php
//server.php
//Punto de entrada principal del backend
//separacion de responsabilidades, configura y delega

/**
 * DEBUG MODE (SOLO EN MODO DESAROLLO)
 */
ini_set('display_errors', 1); //Muestra errores directamente en pantalla
error_reporting(E_ALL); //Muestra todos los errores posibles

header("Access-Control-Allow-Origin: *"); //Permite que cualquier frontend pueda acceder al backend.
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); //Indica al navegador que metodos HTTP acepta el backend
header("Access-Control-Allow-Headers: Content-Type");//Permite que el navegador envie encabezados personalizados

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { //en caso de un metodo options
    http_response_code(200); //manda un mensaje de ok, pero no hace nada
    exit();
}

require_once("./routes/studentsRoutes.php"); //incluye las rutas
?>
