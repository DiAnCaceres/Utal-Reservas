<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reserva\ImplementoRequest;
use App\Models\Bloques;
use App\Models\Implemento;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImplementoController extends Controller
{
    
    public function post_registrar(ImplementoRequest $request){
        $sql=true;
        try {
            $validatedData = $request->validated();
            //OBTENGO EL ID DE LA UBICACION QUE SE SELECIONÓ
            // $nom_ubi = $request->input('nombre_ubicacion');
            $nom_ubi=$validatedData['nombre_ubicacion'];
            $ubi = DB::table("ubicaciones")->where('nombre_ubicacion', $nom_ubi)->first();
            $id_ubicacion = $ubi->id;

            //OBTENGO EL ID DEL ESTADO DISPONIBLE
            $estado = DB::table("estado_reservas")
            ->where('nombre_estado',"Disponible")
            ->first();
            $id_estado=$estado->id;

            DB::table("reservas")->insert([
                "nombre" => $request->nombre,
                "estado_reserva_id" => $id_estado,
                "ubicacione_id" => $id_ubicacion
            ]);
            $id_reserva = DB::getPdo()->lastInsertId();

            DB::table("implementos")->insert([
                "reserva_id" => $id_reserva,
                "cantidad" => $request->cantidad,
            ]);
            return back()->with("success","Implemento registrado correctamente");
        } catch (\Throwable $th) {
            return back()->with('error', '¡Hubo un error al guardar el registro!');
        }
    }
    public function get_registrar(){
        $ubicacionesDeportivas = Ubicacion::where('categoria', 'deportivo')->whereNotIn('nombre_ubicacion',['aire libre'])->get();
        $id_bloque=1;
        return view('implemento.registrar', compact('ubicacionesDeportivas'));
    }

    public function get_reservar(){
        $bloquesDisponibles = Bloques::all();
        return view('implemento.reservar',compact('bloquesDisponibles'));
    }

    public function get_reservar_filtrado(){
        try {
            $datos = session('datos');
            $implementosDisponible = $datos['implementosDisponible'];
            $id_bloque = $datos['id_bloque'];
            $fecha_reserva = $datos['fecha_reserva'];
            return view('Implemento.reservar_filtrado', compact('implementosDisponible', 'id_bloque', 'fecha_reserva'));

        } catch (\Throwable $th) {
            //throw $th;
            // return back()->with('error', 'Salió mal'); 
        }

        return redirect()->route('implemento_reservar');
    }

    public function get_modificarcantidad_agregar(){
        $implementosDisponibles = Implemento::join('reservas','implementos.reserva_id','=','reservas.id')->get(['reservas.nombre','implementos.cantidad']);
        //  dd($implementosDisponibles);
        return view('implemento.agregar',compact('implementosDisponibles'));
    }

    public function get_modificarcantidad_eliminar(){
        $implementosDisponibles = Implemento::join('reservas','implementos.reserva_id','=','reservas.id')->where('implementos.cantidad','>',0)->get(['reservas.nombre','implementos.cantidad']);
        return view('implemento.eliminar',compact('implementosDisponibles'));
    }

    public function post_reservar(Request $request){
        
        try {
            $validatedData = $request->validated();
            // //OBTENGO EL ID DEL BLOQUE QUE SE SELECIONÓ
            // $id_bloque=$request->input('bloques');
            $bloque = $validatedData['id_bloque'];
            $id_bloque = DB::table("bloques")->find($bloque);
            $id_bloque = $id_bloque->id;
            //OBTENER FECHA DE LA RESERVA
            // $fecha_reserva=$request->input('fecha');
            $fecha_reserva=$validatedData['fecha'];

            $consulta = "SELECT * FROM implementos
                INNER JOIN reservas ON reservas.id = implementos.reserva_id
                WHERE reservas.id NOT IN (
                SELECT reservas.id FROM instancia_reservas
                INNER JOIN reservas ON reservas.id = instancia_reservas.reserva_id
                WHERE instancia_reservas.fecha_reserva = ? AND instancia_reservas.bloque_id = ?)";

            $implementosDisponible=DB::select($consulta, [$fecha_reserva, $id_bloque]);
            $datos = ["implementosDisponible" => $implementosDisponible, 'id_bloque' => $id_bloque, 'fecha_reserva' => $fecha_reserva];    
            return redirect()->route('implemento_reservar_filtrado')->with('datos', $datos);
        } catch (\Throwable $th) {
            return back()->with('error', 'Salió mal');
        }
         
    }

    public function post_reservar_filtrado(Request $request){
        try{
            $id_usuario= Auth::user()->id;
            $id_bloque=$request->input('bloque');
            $id_cancha = $request->input('seleccionImplemento');
            // $sala_estudio = DB::table("reservas")->find($id_sala_estudio); //Busco el registro
            $fecha_reserva=$request->input('fecha');
            DB::table("instancia_reservas")->insert([
                "fecha_reserva" => $fecha_reserva,
                "reserva_id" => $id_cancha,
                "user_id" => $id_usuario,
                "bloque_id" => $id_bloque,
            ]);

            $estado_instancia_reserva = DB::table("estado_instancia_reservas")->where('nombre_estado', "reservado")->first();
            $id_estado_instancia = $estado_instancia_reserva->id;

            DB::table("historial_reservas")->insert([
                "instancia_reserva_fecha_reserva"=>6,
                "instancia_reserva_user_id"=>$id_usuario,
                "instancia_reserva_bloque_id"=>$id_bloque,
                "estado_instancia_reserva_id"=>$id_estado_instancia,
                "fecha"=>$fecha_reserva,      //ESTA ES EL DÍA EN QUE SER RESERVÓ
            ]);

            return redirect()->route('implemento_reservar');
        }catch (\Throwable $th){
            return back()->with('error', '¡Hubo un error al reservar!');
        }
    }
}
