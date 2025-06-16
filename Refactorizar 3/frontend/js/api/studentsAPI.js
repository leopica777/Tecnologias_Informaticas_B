/**
*    File        : frontend/js/api/studentsAPI.js
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

/*Es un módulo de frontend que se encarga de comunicar la app con el backend específicamente para el recurso “students”*/

import { createAPI } from './apiFactory.js'; //importacion de funcion, Trae createAPI desde apiFactory donde esta definida 
export const studentsAPI = createAPI('students'); //exportacion de objeto api. se crea un objeto Students API, y pasa el string students como nombre de modulo.

