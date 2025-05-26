//frontDispatcher_2.0
const API_URL = '../backend/server.php'; //DEFINE LA URL DEL BACKEND DONDE SE HARAN LAS SOLICITUDES HTTP O LLAMADAS FETCH

document.addEventListener('DOMContentLoaded', () => //ALMACENA REFERENCIAS A LOS ELEMENTOS DEL FORMULARIO Y LA TABLA PARA PODER ACTUALIZAR VALORES
{
    const studentForm = document.getElementById('studentForm');
    const studentTableBody = document.getElementById('studentTableBody');
    const fullnameInput = document.getElementById('fullname');
    const emailInput = document.getElementById('email');
    const ageInput = document.getElementById('age');
    const studentIdInput = document.getElementById('studentId');

    // Leer todos los estudiantes al cargar
    fetchStudents();

    // Formulario: Crear o actualizar estudiante 
    studentForm.addEventListener('submit', async (e) => { //CANCELA EL ENVIO TRADICIONAL DEL FORMULARIO
        e.preventDefault();

        const formData = { //CREA UN OBJETO CON LOS VALORES DE UN FORMULARIO
            fullname: fullnameInput.value,
            email: emailInput.value,
            age: ageInput.value,
        };

        //DETERMINA METODO PUT (EDITAR) O POST (CREAR)
        const id = studentIdInput.value;
        const method = id ? 'PUT' : 'POST';
        if (id) formData.id = id;

        try //ENVIA SOLICITUD AL SERVIDOR CON LOS DATOS CONVERTIDOS EN JSON
        {
            const response = await fetch(API_URL, {
                method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData),
            });

            if (response.ok) {//RESPUESTA OK
                studentForm.reset();//LIMPIA FORMULARIO
                studentIdInput.value = '';//BORRA ID OCULTO
                await fetchStudents();//RECARGA TABLA
            } else {
                alert("Error al guardar");//MUESTRA ERROR
            }
        } catch (err) {
            console.error(err);
        }
    });

    // Obtener TODOS LOS estudiantes y renderizar tabla MEDIANTE GET
    async function fetchStudents() 
    {
        try 
        {
            const res = await fetch(API_URL);
            const students = await res.json();

            //Limpiar tabla de forma segura.
            studentTableBody.replaceChildren(); //LIMPIA LA TABLA
            //acá innerHTML es seguro a XSS porque no hay entrada de usuario
            //igual no lo uso.
            //studentTableBody.innerHTML = "";

            students.forEach(student => { //ITERA CADA ESTUDIANTE Y CREA UNA NUEVA FILA 
                const tr = document.createElement('tr');

                //CREA CELDAS PARA NOMBRE,EMAIL Y EDAD. TAMBIEN LAS RELLENA
                const tdName = document.createElement('td'); 
                tdName.textContent = student.fullname;

                const tdEmail = document.createElement('td');
                tdEmail.textContent = student.email;

                const tdAge = document.createElement('td');
                tdAge.textContent = student.age;

                //CREA CELDA PARA BOTONES EDITAR Y BORRAR
                const tdActions = document.createElement('td');
                const editBtn = document.createElement('button');

                //BOTON PARA EDITAR CON ESTILOS DE LA W3SCHOOL: CARGAR LOS DATOS DEL ESTUDIANTE AL FORMULA Y GUARDA SU ID
                editBtn.textContent = 'Editar';
                editBtn.classList.add('w3-button', 'w3-blue', 'w3-small', 'w3-margin-right');
                editBtn.onclick = () => {
                    fullnameInput.value = student.fullname;
                    emailInput.value = student.email;
                    ageInput.value = student.age;
                    studentIdInput.value = student.id;
                };

                //Cuando se hace clic en "Borrar", llama a deleteStudent() con el ID del estudiante
                const deleteBtn = document.createElement('button'); 
                deleteBtn.textContent = 'Borrar';
                deleteBtn.classList.add('w3-button', 'w3-red', 'w3-small');
                deleteBtn.onclick = () => deleteStudent(student.id);

                //AGREGA AMBOS BOTONES A LA CELDA DE ACCIONES
                tdActions.appendChild(editBtn);
                tdActions.appendChild(deleteBtn);

                //ENSAMBLA TODA LA FILA Y LA AGREGA AL CUERPO DE LA TABLA.
                tr.appendChild(tdName);
                tr.appendChild(tdEmail);
                tr.appendChild(tdAge);
                tr.appendChild(tdActions);

                studentTableBody.appendChild(tr);
            });
        } catch (err) {
            console.error("Error al obtener estudiantes:", err);
        }
    }

    // Eliminar estudiante
    async function deleteStudent(id) 
    {
        if (!confirm("¿Seguro que querés borrar este estudiante?")) return; // MENSAJE DE CONFIRMACION

        try 
        {
            const response = await fetch(API_URL, { //ENVIA SOLICITUD DELETE CON EL ID DEL ESTUDIANTE
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id }),
            });

            if (response.ok) {
                await fetchStudents(); //RECARGA TABLA
            } else {
                alert("Error al borrar"); //ERORR
            }
        } catch (err) {
            console.error(err);
        }
    }
});
