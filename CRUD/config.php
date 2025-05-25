<?php
/*CONFIG.PHP
    Configura y establece una conexion con la BD MYSQL
*/
/**
 * Datos de conexión: en variables en php.
 */
$host = "localhost";
$user = "students_user";
$password = "12345";
$database = "students_db";

/**
 * $connection "variable", objeto instancia de mysqli
 Crea un nuevo objeto mysqli y establece la conexión usando las credenciales anteriores.
 */
$connection = new mysqli($host, $user, $password, $database);

/**
 * Verificar conexión, si hay error corto interpretación con la función "die"
 * y muestro por pantalla el error. "Modo Desarrollo"
 */ 
if ($connection->connect_error) {
    die("Error de conexión: " . $connection->connect_error);
}
?>
