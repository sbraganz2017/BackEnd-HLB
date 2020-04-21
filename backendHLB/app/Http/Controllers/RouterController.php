<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Router;
use App\Models\Equipo;
use App\Models\Ip;

class RouterController extends Controller
{
    public function listar_router()
    {
        return Router::select('routers.id_router', 'equipos.codigo', 'routers.nombre', 'routers.pass', 'routers.puerta_enlace', 'routers.usuario',
        'routers.clave', 'routers.id_equipo', 'marcas.id_marca', 'marcas.nombre as marca', 'equipos.id_equipo', 'equipos.modelo', 
        'equipos.numero_serie', 'equipos.estado_operativo', 'equipos.descripcion', 'departamentos.nombre as departamento',
        'organizaciones.bspi_punto', 'equipos.ip', 'empleados.nombre as nempleado', 'empleados.apellido')
        ->join('equipos','equipos.id_equipo','=','routers.id_equipo')
        ->join('marcas','marcas.id_marca','=','equipos.id_marca')
        ->leftjoin('empleados','empleados.cedula', '=', 'equipos.asignado')
        ->leftjoin('departamentos', 'empleados.id_departamento', '=', 'departamentos.id_departamento')
        ->leftjoin('organizaciones', 'organizaciones.id_organizacion', '=', 'departamentos.id_organizacion')
        ->orderBy('routers.id_router', 'DESC')
        ->where('equipos.estado_operativo', '<>', 'B')
        ->get();
    }

    public function buscar_router_por_id($id)
    {
      return Router::select('routers.nombre', 'routers.pass', 'routers.usuario', 'routers.clave', 
      'routers.puerta_enlace', 'equipos.id_marca', 'equipos.modelo', 'equipos.numero_serie',
      'equipos.estado_operativo', 'equipos.descripcion', 'equipos.ip', 'equipos.asignado')
      ->where('routers.id_router','=',$id)
      ->join('equipos','equipos.id_equipo','=','routers.id_equipo')
      ->get();
    }

    public function crear_equipo_router(Request $request)
    {
        $equipo = new Equipo();
        $router = new Router();   

        $equipo->fecha_registro = $request->get('fecha_registro');
        $equipo->estado_operativo = $request->get('estado_operativo');
        $equipo->codigo = $request->get('codigo');
        $equipo->tipo_equipo = $request->get('tipo_equipo');
        $equipo->id_marca = $request->get('id_marca');
        $equipo->modelo = $request->get('modelo');
        $equipo->numero_serie = $request->get('numero_serie');
        $equipo->descripcion = $request->get('descripcion');
        $equipo->asignado = $request->get('asignado');
        $equipo->encargado_registro = $request->get('encargado_registro');
        $equipo->componente_principal = $request->get('componente_principal');
        $equipo->ip = $request->get('ip');
        $equipo->save(); 

        $id_equip = $equipo->id_equipo;
        $router->id_equipo = $id_equip;
        $router->nombre = $request->get('nombre');
        $router->pass = $request->get('pass');
        $router->puerta_enlace = $request->get('puerta_enlace');
        $router->usuario = $request->get('usuario');
        $router->clave = $request->get('clave');
        $id = $request->get('ip');
        if($id!==null){
            $ip= Ip::find($id);
            $ip->estado= "EU";
            $ip->save();
        }   
        $router->save();    
    }

    public function marcas_routers(){
        return Router::select('marcas.id_marca', 'marcas.nombre')
        ->join('equipos','equipos.id_equipo','=','routers.id_equipo')
        ->join('marcas','marcas.id_marca','=','equipos.id_marca')
        ->where('equipos.tipo_equipo','Router')
        ->distinct()
        ->get();
    }

    public function filtrar_routers($marca, $fecha_registro=null){
        $query= Router::select('routers.id_router', 'routers.nombre', 'routers.pass', 'routers.puerta_enlace', 'routers.usuario',
        'routers.clave', 'routers.id_equipo')
        ->join('equipos','equipos.id_equipo','=','routers.id_equipo')  
        ->join('marcas','marcas.id_marca','=','equipos.id_marca');
        
        if($marca != "Todas" && !empty($fecha_registro)){
            $query= $query->where([['routers.created_at', 'like', "${fecha_registro}%"],
                ['marcas.nombre', '=', $marca]]);
        }
        if ($marca != "Todas" && empty($fecha_registro)){
            $query= $query->where('marcas.nombre', $marca);
        }
        if ($marca == "Todas" && !empty($fecha_registro)){
            $query= $query->whereDate('routers.created_at', $fecha_registro);
        }
        return  $query->get();
    }

    public function buscar_router($codigo){
        return Router::select('equipos.codigo','routers.id_router', 'routers.nombre', 'routers.pass', 'routers.puerta_enlace', 'routers.usuario',
        'routers.clave', 'routers.id_equipo')
        ->join('equipos','equipos.id_equipo','=','routers.id_equipo')
        ->where('equipos.codigo','like',"%".strtolower($codigo)."%")
        ->orderBy('equipos.created_at', 'desc')
        ->get();
    }

    public function eliminar_router($id)
    {
        $equipo = Equipo::find($id);
        $equipo->estado_operativo = 'B';
        $ip_old=$equipo->ip;
            if($ip_old!==null){
                Ip::Where("id_ip","=",$ip_old)->update(['estado' => "L"]);
                $equipo->ip = null;
            }
        $equipo->save();     
    }

    /*Web service*/
    public function editar_router(Request $request)
    {
      $equipo = Equipo::find($request->id_equipo); 
      $ip_anterior= $equipo->ip; 
      $equipo->fecha_registro = $request->get('fecha_registro');      
      $equipo->estado_operativo = $request->get('estado_operativo');
      $equipo->codigo = $request->get('codigo');
      $equipo->tipo_equipo = $request->get('tipo_equipo');
      $equipo->id_marca = $request->get('id_marca');
      $equipo->modelo = $request->get('modelo');
      $equipo->numero_serie = $request->get('numero_serie');
      $equipo->descripcion = $request->get('descripcion');
      $equipo->asignado = $request->get('asignado');
      $equipo->encargado_registro = $request->get('encargado_registro');
      $equipo->componente_principal = $request->get('componente_principal');
      
      $ip_actual = $request->get('ip');
        if($ip_actual!==null){
            if($ip_anterior!==$ip_actual){
                $ip= Ip::find($ip_actual);
                $ip->estado= "EU";
                $ip->save();
            }
        }else{
            $ip_actual=null;
        }
      
        $equipo->ip = $request->get('ip'); 
        if($ip_anterior!==null){
            $anterior= Ip::find($ip_anterior);
            $anterior->estado= "L";
            $anterior->save();
        }
      $equipo->save(); 

      $router = Router::Where("id_equipo","=",$request->id_equipo)->update([
        "nombre" => $request->get('nombre'),
        "pass" => $request->get('pass'),
        "puerta_enlace" => $request->get('puerta_enlace'),
        "usuario" => $request->get('usuario'),
        "clave" => $request->get('clave')
      ]);  
    }

    /*Método usado en la aplicación movil*/
    public function editar_equipo_router(Request $request)
    {
      $router = Router::find($request->id_equipo);
      $equipo = Equipo::find($router->id_equipo);   
      $ip_anterior= $equipo->ip; 
      $equipo->fecha_registro = $request->get('fecha_registro');      
      $equipo->estado_operativo = $request->get('estado_operativo');
      $equipo->codigo = $request->get('codigo');
      $equipo->tipo_equipo = $request->get('tipo_equipo');
      $equipo->id_marca = $request->get('id_marca');
      $equipo->modelo = $request->get('modelo');
      $equipo->numero_serie = $request->get('numero_serie');
      $equipo->descripcion = $request->get('descripcion');
      $equipo->asignado = $request->get('asignado');
      $equipo->encargado_registro = $request->get('encargado_registro');
      $equipo->componente_principal = $request->get('componente_principal');
      
      $ip_actual = $request->get('ip');
        if($ip_actual!==null){
            if($ip_anterior!==$ip_actual){
                $ip= Ip::find($ip_actual);
                $ip->estado= "EU";
                $ip->save();
            }
        }else{
            $ip_actual=null;
        }
      
        $equipo->ip = $request->get('ip'); 
        if($ip_anterior!==null){
            $anterior= Ip::find($ip_anterior);
            $anterior->estado= "L";
            $anterior->save();
        }
      $equipo->save(); 

      $router->nombre = $request->get('nombre');
      $router->pass = $request->get('pass');
      $router->puerta_enlace = $request->get('puerta_enlace');
      $router->usuario = $request->get('usuario');
      $router->clave = $request->get('clave');
      $router->save();    
    }

    /* Obtener datos de un router dado el id del equipo */
    public function router_id($id_equipo){
        return Router::selectRaw('routers.*, equipos.*, marcas.nombre as marca, 
        empleados.nombre as empleado, empleados.apellido as apellido,
         organizaciones.bspi_punto, departamentos.nombre as departamento' )
        ->join('equipos','equipos.id_equipo','=','routers.id_equipo')
        ->join('marcas','marcas.id_marca','=','equipos.id_marca')
        ->leftjoin('empleados','equipos.asignado','=','cedula')
        ->leftjoin('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->leftjoin('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion')
        ->where('routers.id_equipo',$id_equipo)
        ->get()[0];



}

}
