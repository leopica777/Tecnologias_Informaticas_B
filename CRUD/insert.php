<?php
include 'config.php'; //INCLUYE CONFIG.PHP
/*CREATE.PHP o INSERT.PHP
    Permite agregar nuevos registros a la BD.
*/

/**
 * $_SERVER con esta "super-global" detecto con qué método
 * consultan al servidor.
 * https://www.php.net/manual/es/reserved.variables.request.php
 * https://www.php.net/manual/es/language.variables.superglobals.php 
 */

/*EL CLIENTE MANDA UNA SOLICITUD POST CON LOS DATOS QUE SE DESEAN AGREGAR (JSON)
*/
if ($_SERVER["REQUEST_METHOD"] == "POST") { //VERIFICA QUE EL FORMULARIO FUE ENVIADO POR POST
    $name = $_POST['fullname']; //RECIBE LOS DATOS ENVIADOS DEL FORMULARIO Y GUARDA EN VARIABLES
    $email = $_POST['email'];
    $age = $_POST['age'];
    
    /*El servidor recibe la solicitud y la procesa, creando un nuevo registro en la BD*/
    //ARMA CONSULTA SQL INSERT PARA AGREGAR A UN NUEVO ESTUDIANTE EN LA TABLA
    $sql = "INSERT INTO students (fullname, email, age) 
            VALUES ('$name', '$email', $age)";

    /*El servidor responde con el objeto creado, generalmente con un codigo de estado HTTP*/
    if ($connection->query($sql) === TRUE) { //SI SE EJECUTA BIEN INSERTA LA CONSULTA
        /**
         * la función header redirige a la página principal index.php
         * de lo contrario recargaría la misma página.
         */
        header("Location: index.php"); //REDIRIGE A LA PAGINA PRINCIPAL INDEX
        exit;
    } else {
        echo "Error al insertar: " . $connection->error; //ERROR SE MUESTRA POR PANTALLA
    }
}
?>

<h2>Agregar Estudiante</h2>
<form action="insert.php" method="post">
    Nombre completo: <input type="text" name="fullname" required><br>
    Email: <input type="email" name="email" required><br>
    Edad: <input type="number" name="age" required><br>
    <input type="submit" value="Guardar">
</form>
