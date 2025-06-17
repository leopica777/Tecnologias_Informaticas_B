<?php
/**
*    File        : backend/config/databaseConfig.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

/*Este archivo tiene la función de establecer la conexión con la
base de datos MySQL.
*/

$host = "localhost"; //Direccion del servidor de la base de datos
$user = "students_user_3"; //Usuario Mysql con permisos en la base de datos 
$password = "12345"; //Contraseña asociada al usuario
$database = "students_db_3"; //Nombre de la base de datos

$conn = new mysqli($host, $user, $password, $database); //Conexion con mysql

if ($conn->connect_error)  //verificacion de error en la conexion
{
    http_response_code(500);
    die(json_encode(["error" => "Database connection failed"]));
}
?>
