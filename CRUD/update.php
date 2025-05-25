<?php
/*UPDATE.PHP
Permite modificar un registro existente en la BD
*/
include 'config.php'; //INCLUYE ARCHIVO CONFIG.PHP

$id = $_GET['id']; //TOMA EL ID DEL ESTUDIANTE DESDE LA URL
$result = $connection->query("SELECT * FROM students WHERE id = $id"); //CONSULTA SQL PARA ENCONTRAR AL ESTUDIANTE CON ESE ID
$row = $result->fetch_assoc(); //EXTRAE ESA FILA COMO ARRAY ASOCIATIVO

if ($_SERVER["REQUEST_METHOD"] == "POST") { //VERIFICA SI EL FORMULARIO FUE ACTUALIZADO
    $name = $_POST['fullname']; //TOMA VALORES ENVIADOS POR EL FORMULARIO
    $email = $_POST['email'];
    $age = $_POST['age'];

    $sql = "UPDATE students SET fullname='$name', email='$email', age=$age WHERE id=$id"; //PREPARA CONSULTA SQL UPDATE PARA MODIFICAR LOS DATOS DEL ESTUDIANTE

    if ($connection->query($sql) === TRUE) { //EJECUTA LA CONSULTA
        header("Location: index.php"); //REDIRIGE A INDEX
        exit;
    } else {
        echo "Error al actualizar: " . $connection->error; //ERROR SI FALLA EL UPDATE
    }
}
?>

<h2>Editar Estudiante</h2>
<!--<form method="post">--> <!--NO SE HACE si no especifo action, usa la url actual con el id por GET-->

<!-- En el action se agrega el id de la fila que estoy editando--> 
<form action="update.php?id=<?= $row['id'] ?>" method="post">
    Nombre completo: <input type="text" name="fullname" value="<?= $row['fullname'] ?>" required><br>
    Email: <input type="email" name="email" value="<?= $row['email'] ?>" required><br>
    Edad: <input type="number" name="age" value="<?= $row['age'] ?>" required><br>
    <input type="submit" value="Actualizar">
</form>
