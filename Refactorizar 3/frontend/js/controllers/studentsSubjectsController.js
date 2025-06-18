/**
*    File        : frontend/js/controllers/studentsSubjectsController.js
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

//import de APIS
import { studentsAPI } from '../api/studentsAPI.js';//estudaintes
import { subjectsAPI } from '../api/subjectsAPI.js';//materias
import { studentsSubjectsAPI } from '../api/studentsSubjectsAPI.js';//relaciones entre ellos

//Inicializacion principal
document.addEventListener('DOMContentLoaded', () => 
{
    initSelects();//cargar los selects con estudiantes y materias 
    setupFormHandler();//se configura el evento submit
    setupCancelHandler();//se configura boton cancelar
    loadRelations();//se carga la tabla de relaciones actuales
});

async function initSelects() //Carga estudiantes y materias en sus respectivos <select>.
{
    try 
    {
        // Cargar estudiantes
        const students = await studentsAPI.fetchAll();
        const studentSelect = document.getElementById('studentIdSelect');
        students.forEach(s => 
        {
            const option = document.createElement('option');
            option.value = s.id;
            option.textContent = s.fullname;
            studentSelect.appendChild(option);
        });

        // Cargar materias
        const subjects = await subjectsAPI.fetchAll();
        const subjectSelect = document.getElementById('subjectIdSelect');
        subjects.forEach(sub => 
        {
            const option = document.createElement('option');
            option.value = sub.id;
            option.textContent = sub.name;
            subjectSelect.appendChild(option);
        });
    } 
    catch (err) //si hay error se captura
    {
        console.error('Error cargando estudiantes o materias:', err.message);
    }
}

function setupFormHandler() //maneja el envio del formulario
{
    const form = document.getElementById('relationForm');
    form.addEventListener('submit', async e => 
    {
        e.preventDefault();

        const relation = getFormData(); //lama getformdata para armar objeto

        try 
        {
            if (relation.id)  //si hay id, hace update
            {
                await studentsSubjectsAPI.update(relation);
            } 
            else //sino create
            {
                await studentsSubjectsAPI.create(relation);
            }
            clearForm();
            loadRelations();
        } 
        catch (err) 
        {
            console.error('Error guardando relación:', err.message);
        }
    });
}

function setupCancelHandler() //Resetea el formulario y limpia el campo oculto relationId.
{
    const cancelBtn = document.getElementById('cancelBtn');
    cancelBtn.addEventListener('click', () => 
    {
        document.getElementById('relationId').value = '';
    });
}

function getFormData()  //Extrae y devuelve un objeto javascript con los valores actuales del formulario
{
    return{
        id: document.getElementById('relationId').value.trim(),
        student_id: document.getElementById('studentIdSelect').value,
        subject_id: document.getElementById('subjectIdSelect').value,
        approved: document.getElementById('approved').checked ? 1 : 0 //convierte checkbox a entero
    };
}

function clearForm()  //Resetea todos los campos del formulario y limpia el campo oculto relationId

{
    document.getElementById('relationForm').reset();
    document.getElementById('relationId').value = '';
}

async function loadRelations() //Carga desde el backend la lista completa de relaciones.

{
    try 
    {
        const relations = await studentsSubjectsAPI.fetchAll(); //convierte el campo aproved a numero real
        
        /**
         * DEBUG
         */
        //console.log(relations);

        /**
         * En JavaScript: Cualquier string que no esté vacío ("") es considerado truthy.
         * Entonces "0" (que es el valor que llega desde el backend) es truthy,
         * ¡aunque conceptualmente sea falso! por eso: 
         * Se necesita convertir ese string "0" a un número real 
         * o asegurarte de comparar el valor exactamente. 
         * Con el siguiente código se convierten todos los string approved a enteros.
         */
        relations.forEach(rel => 
        {
            rel.approved = Number(rel.approved);
        });
        
        renderRelationsTable(relations); 
    } 
    catch (err) 
    {
        console.error('Error cargando inscripciones:', err.message);
    }
}

function renderRelationsTable(relations)  //Limpia y reconstruye el <tbody> de la tabla usando DOM seguro
{
    const tbody = document.getElementById('relationTableBody');
    tbody.replaceChildren();

    relations.forEach(rel => 
    {
        const tr = document.createElement('tr');

        tr.appendChild(createCell(rel.student_fullname));
        tr.appendChild(createCell(rel.subject_name));
        tr.appendChild(createCell(rel.approved ? 'Sí' : 'No'));
        tr.appendChild(createActionsCell(rel));

        tbody.appendChild(tr);
    });
}

function createCell(text) //Crea los botones de Editar y Borrar, y asigna sus eventos.

{
    const td = document.createElement('td');
    td.textContent = text;
    return td;
}

function createActionsCell(relation) 
{
    const td = document.createElement('td');

    const editBtn = document.createElement('button');
    editBtn.textContent = 'Editar';
    editBtn.className = 'w3-button w3-blue w3-small';
    editBtn.addEventListener('click', () => fillForm(relation));

    const deleteBtn = document.createElement('button');
    deleteBtn.textContent = 'Borrar';
    deleteBtn.className = 'w3-button w3-red w3-small w3-margin-left';
    deleteBtn.addEventListener('click', () => confirmDelete(relation.id));

    td.appendChild(editBtn);
    td.appendChild(deleteBtn);
    return td;
}

function fillForm(relation) //Llena el formulario con los datos de una relación seleccionada para edición.

{
    document.getElementById('relationId').value = relation.id;
    document.getElementById('studentIdSelect').value = relation.student_id;
    document.getElementById('subjectIdSelect').value = relation.subject_id;
    document.getElementById('approved').checked = !!relation.approved;
}

async function confirmDelete(id) //Confirma el borrado con window.confirm(), y si el usuario acepta, llama a studentsSubjectsAPI.remove(id).

{
    if (!confirm('¿Estás seguro que deseas borrar esta inscripción?')) return;

    try 
    {
        await studentsSubjectsAPI.remove(id);
        loadRelations();
    } 
    catch (err) 
    {
        console.error('Error al borrar inscripción:', err.message);
    }
}
