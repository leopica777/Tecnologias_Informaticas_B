<?php
/**
*    File        : backend/controllers/studentsController.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

/*Este archivo es un controlador. Es decir, define funciones que
se ejecutan cuando el usuario hace una operación sobre los
estudiantes: ver, crear, modificar o eliminar.
*/

require_once("./models/students.php");//incluye el modelo de estudiantes

function handleGet($conn) //Funcion HandleGet. Se ejecuta con una peticion get
{
    $input = json_decode(file_get_contents("php://input"), true); //Intenta leer el contenido del cuerpo de la peticion y convertirlo desde JSON a un array de PHP

    
    if (isset($input['id'])) //si el json recibido tiene un id
    {
        $student = getStudentById($conn, $input['id']); //se busca un unico estudiante con ese id
        echo json_encode($student); //la respuesta se vuelve a json
    } 
    else//SINO
    {
        $students = getAllStudents($conn);//se devuelven todos los estudiantes
        echo json_encode($students);//la respuesta se vuelve a json
    }
}

function handlePost($conn) //Se ejecuta cuando el cliente hace una peticion POST (creacion)
{
    $input = json_decode(file_get_contents("php://input"), true);//Se convierte el JSON recibido en un array.

    $result = createStudent($conn, $input['fullname'], $input['email'], $input['age']); //Llama a la funcion createstudents pasando los datos del formulario
    if ($result['inserted'] > 0) //la creacion fue exitosa
    {
        echo json_encode(["message" => "Estudiante agregado correctamente"]);
    } 
    else 
    {
        http_response_code(500);//error
        echo json_encode(["error" => "No se pudo agregar"]);
    }
}

function handlePut($conn) //Se ejecuta con una peticion PUT (actualizacion)
{
    $input = json_decode(file_get_contents("php://input"), true);//se leen datos en formato json

    $result = updateStudent($conn, $input['id'], $input['fullname'], $input['email'], $input['age']);//lee el id a actualizar. updateStudents
    if ($result['updated'] > 0) //actualizacion fue exitosa
    {
        echo json_encode(["message" => "Actualizado correctamente"]);
    } 
    else 
    {
        http_response_code(500);//error
        echo json_encode(["error" => "No se pudo actualizar"]);
    }
}

function handleDelete($conn) //peticion DELETE
{
    $input = json_decode(file_get_contents("php://input"), true);

    $result = deleteStudent($conn, $input['id']);//lee el id a eliminar
    if ($result['deleted'] > 0) 
    {
        echo json_encode(["message" => "Eliminado correctamente"]);//exito
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo eliminar"]);//error
    }
}
?>
