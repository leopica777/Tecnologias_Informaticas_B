/**
*    File        : frontend/js/api/apiFactory.js
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

/*Este archivo define la función genérica createAPI() que
permite crear objetos de acceso al backend para cualquier
módulo 
Esto nos sirve para la reutilizacion de codigo , generalizacion, modularidad.
*/

export function createAPI(moduleName, config = {}) //Se exporta la funcion CreateAPI
{
    //Construccion de la URL de acceso al servidor
    const API_URL = config.urlOverride ?? `../../backend/server.php?module=${moduleName}`; //Si esta definido, se usa ese valor como URL personalizada. Sino se construye una URL estándar
    

    async function sendJSON(method, data) //Funcion auxiliar interna. Se usa para enviar datos al servidor usando los metodos HTTP. (POST, PUT, DELETE)
    {
        const res = await fetch(API_URL, //Peticion FETCH al backend con GET
        {
            method, //POST, PUT O DELETE
            headers: { 'Content-Type': 'application/json' }, //Indica que se envia por JSON
            body: JSON.stringify(data) //Convierte los datos a JSON antes de enviarlos
        });

        if (!res.ok) throw new Error(`Error en ${method}`); //Si la respuesta del servidor no es exitosa, se lanza error
        return await res.json();//Si esta todo bien, la respuesta se vuelve un objeto JS y se devuelve
    }

    return { //Retorno de CreateAPI, devuelve un objeto con las 4 funciones de CRUD
        async fetchAll() //Obtiene todos los registros del modulo desde el servidor 
        {
            const res = await fetch(API_URL); //Usa fetch para hacer una peticion GET simple
            if (!res.ok) throw new Error("No se pudieron obtener los datos"); //Error si no es valido
            return await res.json(); //Devuelve los datos en objeto JSON
        },
        async create(data) //Crear un nuevo registro en el backend
        {
            return await sendJSON('POST', data); //Funcion auxiiliar sendJSON con metodo POST.
        },
        async update(data)
        {
            return await sendJSON('PUT', data);//Funcion auxiiliar sendJSON con metodo PUT.
        },
        async remove(id)
        {
            return await sendJSON('DELETE', { id });//Elimina un registro del backend, usando DELETE
        }
    };
}
