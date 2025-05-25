<?php
//Students.php MODELS
//Contiene todas las funcones relacionadas con el acceso a la BD para el modulo de estudiantes
//Su unico rol es interactuar con el BD usando SQL
//Solo obtiene datos, no muestra informacion

function getAllStudents($conn) { //Define una función que recibe la conexión a la base de datos como parametro. Obtiene todos los estudiantes
    $sql = "SELECT * FROM students"; //Consulta SQL, selecciona todos los registros de la tabla de estudiantes
    return $conn->query($sql); //Ejecuta la consulta y devuelve el resultado
}

function getStudentById($conn, $id) { //Funcion para obtener solamente a un estudiante por su id
    $sql = "SELECT * FROM students WHERE id = ?"; //consulta con el marcador ? para evitar INYECCIONES DE SQL
    $stmt = $conn->prepare($sql); //prepara la consulta
    $stmt->bind_param("i", $id); //asocia el parametro i (entero) que sera reemplazado por la id
    $stmt->execute(); //ejecuta la consulta
    return $stmt->get_result(); //devuelve el resultado
}

function createStudent($conn, $fullname, $email, $age) { //Funcion para insertar nuevo estudiante
    $sql = "INSERT INTO students (fullname, email, age) VALUES (?, ?, ?)"; //Consulta SQL para ingresar los datos del estudiante
    $stmt = $conn->prepare($sql); //prepara la consulta
    $stmt->bind_param("ssi", $fullname, $email, $age); //asocia parametros ssi= string,string,integer.
    return $stmt->execute(); //ejecuta la consulta y devuelve el resultado
}

function updateStudent($conn, $id, $fullname, $email, $age) { //Funcion para actualizar un estudiante
    $sql = "UPDATE students SET fullname = ?, email = ?, age = ? WHERE id = ?"; //actualiza los campos de un estudiante en especifico
    $stmt = $conn->prepare($sql); //prepara la consulta
    $stmt->bind_param("ssii", $fullname, $email, $age, $id); //asocia parametros
    return $stmt->execute();//ejecuta la consulta y devuelve el resultado
}

function deleteStudent($conn, $id) { //funcion para eliminar estudiante por id
    $sql = "DELETE FROM students WHERE id = ?"; //Consulta SQL para eliminar estudiante
    $stmt = $conn->prepare($sql);//prepara la consulta
    $stmt->bind_param("i", $id);//asocia paramentros
    return $stmt->execute();//ejecuta y devuelve 
}
?>
