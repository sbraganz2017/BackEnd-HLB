<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*API Correo*/
    Route::get('mostrar_correos', 'CorreoController@mostrar_correos');
    Route::post('correos', 'CorreoController@crear_correo');
    Route::get('correos/{fecha_asignacion}','CorreoController@buscar_por_fecha');
    Route::get('correos/{fecha_asignacion}/{dpto}','CorreoController@buscar_por_fecha_dpto');

 /*API Empleado*/   
    Route::get('empleados_nombre/{nombreEmpleado}', 'EmpleadoController@buscar_por_nombre');
    Route::get('empleados_punto/{punto}', 'EmpleadoController@buscar_por_punto');
    Route::get('empleados_estado/{estado}', 'EmpleadoController@buscar_por_estado');
    Route::get('empleados_dpto/{departamento}', 'EmpleadoController@buscar_por_departamento');
    Route::get('buscar_empleado/{nombreEmpleado}', 'EmpleadoController@buscar_empleado');
    Route::get('empleados_dpto/{departamento}', 'EmpleadoController@buscar_por_departamento');


/* API EQUIPOS */
    Route::post('desktop','EquipoController@crear_Comp_Desktop');
    Route::post('laptop','EquipoController@crear_Comp_laptop');

    
/*API DEPARTAMENTO*/      
    Route::get('departamentos','DepartamentoController@mostrar_todos');

