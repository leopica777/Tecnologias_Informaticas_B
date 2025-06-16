/**
*    File        : frontend/js/controllers/studentsController.js
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/


/*
Este programa es el controlador del frontend de un sistema CRUD de estudiantes hecho en JavaScript. Se encarga de:
Carga los datos de los estudiantes desde el backend y los muestra en una tabla HTML.

Permite agregar nuevos estudiantes mediante un formulario.

Permite editar estudiantes existentes, llenando el formulario con sus datos.

Permite borrar estudiantes, con confirmación previa.

Maneja eventos del formulario (guardar, cancelar) de forma asincrónica.

Se comunica con el backend usando funciones importadas desde studentsAPI.js.

*/


import { studentsAPI } from '../api/studentsAPI.js'; //importa funciones del StudentsAPI.js que contiene las funciones para comunicarse con el backend
//permite el uso del studentsapicreate() o studentsapiupdate() en dentro de este archivo

document.addEventListener('DOMContentLoaded', () =>  //espera a que el html carge completo
{
    loadStudents(); //carga y muestra los estudiantes en la tabla
    setupFormHandler(); //prepara el formulario para cuando se haga click en guardar, se procese la informacio
    setupCancelHandler(); //configura el comportamiento del boton cancelar
});
  
function setupFormHandler() //configura envio del formulario al enviar submit
{
    const form = document.getElementById('studentForm'); //se busca el formulario con ese id en el html
    form.addEventListener('submit', async e =>  //se configura el formulario para que no recarge la pagina
    {
        e.preventDefault();
        const student = getFormData(); //se extraen los datos del formulario
    
        try  //intenta
        {
            if (student.id) //si hay id
            {
                await studentsAPI.update(student); //edita estudiante
            } 
            else 
            {
                await studentsAPI.create(student); //crea estudiante
            }
            clearForm(); //limpia campos del formulario
            loadStudents(); //recarga la tabla
        }
        catch (err) //atrapa excepcion
        {
            console.error(err.message);
        }
    });
}

function setupCancelHandler() //configura la cancelacion del formulario al hacer click
{
    const cancelBtn = document.getElementById('cancelBtn'); //se obtiene el elemento boton cancelar
    cancelBtn.addEventListener('click', () => //cuando el boton hace click
    {
        document.getElementById('studentId').value = ''; //se borra el campo oculto studentid. Previene que si se elige editar y luego cancela, no sse modifica.
    });
}
  
function getFormData() //obtiene datos del formulario
{
    return {
        id: document.getElementById('studentId').value.trim(), //crea un objeto con los datos del formulario
        fullname: document.getElementById('fullname').value.trim(),//el trim elimina espacios en blanco
        email: document.getElementById('email').value.trim(),
        age: parseInt(document.getElementById('age').value.trim(), 10) //el parseint vuelve al numero un entero
    };
}
  
function clearForm()//limpia el formulario
{
    document.getElementById('studentForm').reset();//el reset limpia todos los campos del formulario
    document.getElementById('studentId').value = '';//se borra manualmente el campo oculto
}
  
async function loadStudents()//carga estudiantes desde el backend
{
    try 
    {
        const students = await studentsAPI.fetchAll();//obtiene todos los estudiantes del backend
        renderStudentTable(students);//muestra por pantalla
    } 
    catch (err) 
    {
        console.error('Error cargando estudiantes:', err.message);
    }
}
  
function renderStudentTable(students)//funcion para mostrar estudiantes en una tabla
{
    const tbody = document.getElementById('studentTableBody'); //se busca el cuerpo de la tabla
    tbody.replaceChildren(); //se eliminan filas anteriores para empezar de cero.
  
    students.forEach(student => //para cada estudiante, se crea una nueva fila
    {
        const tr = document.createElement('tr'); //tr = fila
    
        tr.appendChild(createCell(student.fullname));
        tr.appendChild(createCell(student.email));
        tr.appendChild(createCell(student.age.toString()));
        tr.appendChild(createActionsCell(student)); //celda extra para botones editar/borrar
    
        tbody.appendChild(tr);//final de tabla
    });
}
  
function createCell(text)//funcion para crear una celda
{
    const td = document.createElement('td');//crea una celda td con el texto recibido
    td.textContent = text; //por seguridad interpreta texto. para evitar inyecciones de html
    return td;
}
  
function createActionsCell(student)//Funcion para botones editar y borrar
{
    const td = document.createElement('td');//crea una celda que contendra los botones de accion
  
    const editBtn = document.createElement('button'); //boton editar
    editBtn.textContent = 'Editar';
    editBtn.className = 'w3-button w3-blue w3-small'; //clase azul
    editBtn.addEventListener('click', () => fillForm(student)); //al hacer click se llama a fillform para llenar el formulario
  
    const deleteBtn = document.createElement('button');//boton borrar
    deleteBtn.textContent = 'Borrar';
    deleteBtn.className = 'w3-button w3-red w3-small w3-margin-left';//clase roja
    deleteBtn.addEventListener('click', () => confirmDelete(student.id));//al hacer click pide confirmacion y si se acepta llama a confirmdelete
  
    td.appendChild(editBtn);//se agregan los botones a la celda
    td.appendChild(deleteBtn);
    return td;//retorna elemento td
}
  
function fillForm(student)//funcion carga datos en el formulario
{
    document.getElementById('studentId').value = student.id;//copia los datos del estudiante seleccionados para permitir edicion
    document.getElementById('fullname').value = student.fullname;
    document.getElementById('email').value = student.email;
    document.getElementById('age').value = student.age;
}
  
async function confirmDelete(id) //funcion para confirmar y borrar
{
    if (!confirm('¿Estás seguro que deseas borrar este estudiante?')) return; //muestra cuadro de confirmacion. si el usuario cancela no hace nada
  
    try 
    {
        await studentsAPI.remove(id); //borra en el backend
        loadStudents(); //recarga la tabla
    } 
    catch (err) 
    {
        console.error('Error al borrar:', err.message);
    }
}
  
