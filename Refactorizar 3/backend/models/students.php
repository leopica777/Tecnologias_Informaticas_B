<?php
/**
*    File        : backend/models/students.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

/*
Este archivo define una serie de funciones PHP que ejecutan
consultas SQL sobre la tabla students. Cada función realiza
una acción típica de un CRUD
*/

function getAllStudents($conn) //obtener todos los estudiantes
{
    $sql = "SELECT * FROM students"; //Define una consulta SQL que selecciona todas las filas de la tabla students.


    //MYSQLI_ASSOC devuelve un array ya listo para convertir en JSON:
    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC); //ejecuta la consulta sql. fetchall convierte en array asociativo. 
}

function getStudentById($conn, $id) //obtener estudiante por id
{
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?"); //prepara consulta sql con ? como marcador de posicion
    $stmt->bind_param("i", $id); //remplaza el ? por un entero i
    $stmt->execute(); //ejecuta la consulta
    $result = $stmt->get_result();//obtiene el resultado

    //fetch_assoc() devuelve un array asociativo ya listo para convertir en JSON de una fila:
    return $result->fetch_assoc(); //extrae UNA SOLA fila como array asociativo
}

function createStudent($conn, $fullname, $email, $age) //CREAR ESTUDIANTES
{
    $sql = "INSERT INTO students (fullname, email, age) VALUES (?, ?, ?)";//Prepara consulta INSERT para agregar un nuevo estudiante
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $fullname, $email, $age);
    $stmt->execute();//ejecuta

    //Se retorna un arreglo con la cantidad e filas insertadas 
    //y id insertado para validar en el controlador:
    return //devuelve un array
    [
        'inserted' => $stmt->affected_rows,  //cuantas filas se insertaron      
        'id' => $conn->insert_id //id generado por la BD. Es para validar
    ];
}

function updateStudent($conn, $id, $fullname, $email, $age) //Actualizar estudiante
{
    $sql = "UPDATE students SET fullname = ?, email = ?, age = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $fullname, $email, $age, $id); //ssii = string, string, integer,integer
    $stmt->execute();//ejecuta

    //Se retorna fila afectadas para validar en controlador:
    return ['updated' => $stmt->affected_rows]; //Devuelve cuántas filas fueron modificadas
}

function deleteStudent($conn, $id) //borrar estudiante
{
    $sql = "DELETE FROM students WHERE id = ?";//Prepara consulta DELETE por id
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); //EVITA INYECCIONES DE SQL
    $stmt->execute();//ejecuta

    //Se retorna fila afectadas para validar en controlador
    return ['deleted' => $stmt->affected_rows]; //Devuelve cuántas filas fueron modificadas
}
?>
