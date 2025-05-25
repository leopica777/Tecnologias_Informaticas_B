<?php
/*DELETE.PHP
Permite eliminar un registro de la BD
*/
include 'config.php'; //INCLUYE CONFIG.PHP

$id = $_GET['id']; //OBTIENE EL VALOR DE LA ID QUE SE PASO POR LA URL POR METODO GET

$sql = "DELETE FROM students WHERE id = $id"; //CREA SENTENCIA SQL DELETE PARA BORRAR A UN ESTUDIANTE POR ID

if ($connection->query($sql) === TRUE) { //CONSULTA SI FUE EXITOSA LA CONSULTA
    header("Location: index.php"); //REDIRIGE AL INDEX
    exit;
} else {
    echo "Error al borrar: " . $connection->error; //SI HAY ERROR MUESTRA MENSAJE EN PANTALLA
}
?>
