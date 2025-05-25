<?php
/*CONFIG.PHP
    Configura y establece una conexion con la BD MYSQL
*/
/**
 * Datos de conexión: en variables en php.
 */
$host = "localhost"; //VARIABLES QUE GUARDAN LAS CREDENCIALES
$user = "students_user";
$password = "12345";
$database = "students_db";

/**
 * $connection "variable", objeto instancia de mysqli
 Crea un nuevo objeto mysqli y establece la conexión usando las credenciales anteriores.
 */
$connection = new mysqli($host, $user, $password, $database); //CREA UN NUEVO OBJETO DE CLASE MSQLI QUE REPRESENTA LA CONEXION A LA BD

/**
 * Verificar conexión, si hay error corto interpretación con la función "die"
 * y muestro por pantalla el error. "Modo Desarrollo"
 */ 
if ($connection->connect_error) { //VERIFICA ERRORES 
    die("Error de conexión: " . $connection->connect_error); //CORTA EL SCRIPT INMEDIATAMENTE
}
?>
