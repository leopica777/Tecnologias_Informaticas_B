<?php
//StudentsController.php
//Se encarga de recibir,procesar y responder las solicitudes HTTP

require_once("./models/students.php"); //Incluye el archivo students.php

function handleGet($conn) { //Responde a solicitudes GET, para leer datos
    if (isset($_GET['id'])) { //si se pasa un id, devuelve un estudiante especifico
        $result = getStudentById($conn, $_GET['id']); //llama a la funcion modelo que busca al estudiante en la BD x id
        echo json_encode($result->fetch_assoc()); //fetch_assoc, convierte la fila en un array asociativo
    } else { //sino se pasa id, devuelve todos
        $result = getAllStudents($conn);
        $data = [];//crea un arreglo vacio para guardar resultados
        while ($row = $result->fetch_assoc()) { //recorre cada fila del resultado.
            $data[] = $row; //agrega cada fila al arreglo
        }
        echo json_encode($data); //json_encode, transforma datos a formato json
    }
}

function handlePost($conn) { //responde a solicitudes POST, para crear un nuevo registro
    $input = json_decode(file_get_contents("php://input"), true);//lee el contenido del cuerpo y lo convierte en un array
    if (createStudent($conn, $input['fullname'], $input['email'], $input['age'])) {//llama a la funcion modelo para crear alumnos
        echo json_encode(["message" => "Estudiante agregado correctamente"]); //mensaje de exito
    } else {
        http_response_code(500);//si falla responde con error
        echo json_encode(["error" => "No se pudo agregar"]);
    }
}

function handlePut($conn) {//responde a solicitudes PUT, para actualizar un registro
    $input = json_decode(file_get_contents("php://input"), true); //lee el contenido del cuerpo 
    if (updateStudent($conn, $input['id'], $input['fullname'], $input['email'], $input['age'])) { //llama a la funcion modelo para actualizar alumnos
        echo json_encode(["message" => "Actualizado correctamente"]);//mensaje de exito
    } else {
        http_response_code(500); //si falla responde con error
        echo json_encode(["error" => "No se pudo actualizar"]);
    }
}

function handleDelete($conn) {//responde a solicitudes DELETE, para eliminar un registro
    $input = json_decode(file_get_contents("php://input"), true);//lee el contenido del cuerpo
    if (deleteStudent($conn, $input['id'])) {//llama a la funcion modelo para eliminar alumnos
        echo json_encode(["message" => "Eliminado correctamente"]);//mensaje de exito
    } else {
        http_response_code(500);//si falla responde con error
        echo json_encode(["error" => "No se pudo eliminar"]);
    }
}
?>
