<?php
//INDEX.PHP

/**
 * Este es el script por donde comienza el sitio, el nombre index.php
 * es una convención estándar como puede serlo index.html
 */

/**
 * Al principio se incluye el archivo de configuración, que en este caso no es
 * una mala práctica porque está muy bien tener la conexión a la base de datos
 * en un solo lugar.
 */
include 'config.php'; //INCLUYE CONFIG.PHP

/**
 * uso el objeto connection para ejecutar una consulta
 * a la base de datos.
 * query es una función("método") 
 */
$result = $connection->query("SELECT * FROM students"); //EJECUTA CONSULTA SQL PARA TRAER TODOS LOS REGISTROS DE LA TABLA STUDENTS

/**
 * Con echo mostramos por "pantalla" (navegador web)
 * el html al cliente.
 */
echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<link rel='stylesheet' href='style.css'>";
echo "</head>";

echo "<body>";
echo "<h2>Listado de Estudiantes</h2>";
echo "<a href='insert.php'>Agregar Nuevo</a><br><br>";

if ($result->num_rows > 0) { //SI LA CONSULTA TRAJO RESULTADOS
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Nombre</th><th>Email</th><th>Edad</th><th>Acciones</th></tr>";
    while ($row = $result->fetch_assoc()) { //BUCLE PARA MOSTRAR A CADA ESTUDIANTE
        echo "<tr>
                <td>{$row['fullname']}</td> 
                <td>{$row['email']}</td>
                <td>{$row['age']}</td>
                <td>
                    <a href='update.php?id={$row['id']}'>Editar</a> |
                    <a href='delete.php?id={$row['id']}'>Borrar</a>
                </td>
              </tr>";
    }//MUESTRA LOS DATOS DE CADA ESTUDIANTE Y CREA 2 ENLACES QYE VAN A UPDATE.PHP Y DELETE.PHP CON EL ID DEL ESTUDIANTE
    echo "</table>";
} else {
    echo "No hay estudiantes cargados.";
}
echo "</body>";
echo "</html>";
?>
