<?php
/**
*    File        : backend/models/studentsSubjects.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

function assignSubjectToStudent($conn, $student_id, $subject_id, $approved) //Crea nueva asignación (INSERT)
{
    $sql = "INSERT INTO students_subjects (student_id, subject_id, approved) VALUES (?, ?, ?)"; //insert con placeholders ?? para evitar inyecciones sql
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $student_id, $subject_id, $approved);//se enlanza los valores de los placeholders
    $stmt->execute();

    return 
    [//devuelve cantidad de filas insertadas y el id nuevo registro
        'inserted' => $stmt->affected_rows,        
        'id' => $conn->insert_id
    ];
}

//Query escrita sin ALIAS resumidos (a mi me gusta más):
function getAllSubjectsStudents($conn) ///recupera todas las relaciones estudiante materia. junto con nombres completos de estudiante y materia gracias al JOIN
{//Devuelve todas las relaciones con nombres completos

    $sql = "SELECT students_subjects.id,
                students_subjects.student_id,
                students_subjects.subject_id,
                students_subjects.approved,
                students.fullname AS student_fullname,
                subjects.name AS subject_name
            FROM students_subjects
            JOIN subjects ON students_subjects.subject_id = subjects.id
            JOIN students ON students_subjects.student_id = students.id";

    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);//Devuelve array asociativo multidimensional 
}

//Query escrita con ALIAS resumidos:
function getSubjectsByStudent($conn, $student_id) //no usada
{
    $sql = "SELECT ss.subject_id, s.name, ss.approved
        FROM students_subjects ss
        JOIN subjects s ON ss.subject_id = s.id
        WHERE ss.student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result= $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC); 
}

function updateStudentSubject($conn, $id, $student_id, $subject_id, $approved) 
{
    $sql = "UPDATE students_subjects 
            SET student_id = ?, subject_id = ?, approved = ? 
            WHERE id = ?"; //actualiza una relacion existente con nuevos valores
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $student_id, $subject_id, $approved, $id);
    $stmt->execute();

    return ['updated' => $stmt->affected_rows];//devuelve cuantas filas fueron modificadas
}

function removeStudentSubject($conn, $id) //Elimina la relación estudiante-materia identificada por id.
{
    $sql = "DELETE FROM students_subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return ['deleted' => $stmt->affected_rows];
}
?>
