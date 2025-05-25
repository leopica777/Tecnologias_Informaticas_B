<?php
//CONFIG.PHP
/*Configura y estable una conexion con la BD MYSQL usando la extension MYSQLI*/

$host = "localhost";
$user = "students_user";
$password = "12345";
$database = "students_db";

/*Crea un nuevo objeto SQLI y establece la conexion usando las credenciales anteriores*/
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) { //Verifica si hubo algun error en la conexion
    http_response_code(500); //Si falla responde con un codigo HTTP
    die(json_encode(["error" => "Database connection failed"])); //Envia un mensaje JSON con clave error y detiene la ejecucion del script inmediatamente
}
?>
