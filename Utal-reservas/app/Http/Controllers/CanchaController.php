<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reserva\CanchaRequest;
use App\Models\Bloques;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanchaController extends Controller
{
   
    public function post_registrar(CanchaRequest $request){
        $sql=true;
        try {
            //OBTENGO EL ID DE LA UBICACION QUE SE SELECIONÓ
            $nom_ubi = $request->input('nombre_ubicacion');
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

            DB::table("canchas")->insert([
                "reserva_id" => $id_reserva
            ]);
            return back()->with("success","Cancha registrada correctamente");
        } catch (\Throwable $th) {
            return back()->with('error', '¡Hubo un error al guardar el registro!');
        }
    }

    public function get_reservar(){
        $bloquesDisponibles = Bloques::all();
        return view('cancha.reservar',compact('bloquesDisponibles'));
    }

    public function get_registrar(){
        $ubicacionesDeportivas = Ubicacion::where('categoria', 'deportivo')->get();
        return view('cancha.registrar', compact('ubicacionesDeportivas'));
    }

    public function get_reservar_filtrado(){
        try {
            $datos = session('datos');
            $canchasDisponible = $datos['canchasDisponible'];
            $id_bloque = $datos['id_bloque'];
            $fecha_reserva = $datos['fecha_reserva'];
            return view('Cancha.reservar_filtrado', compact('canchasDisponible', 'id_bloque', 'fecha_reserva'));

        } catch (\Throwable $th) {
            //throw $th;
            // return back()->with('error', 'Salió mal'); 
        }

        return redirect()->route('cancha_reservar');
    }

    public function post_reservar(Request $request){
        
        try {
            //OBTENGO EL ID DEL BLOQUE QUE SE SELECIONÓ
            $id_bloque=$request->input('bloques');

            //OBTENER FECHA DE LA RESERVA
            $fecha_reserva=$request->input('fecha');

            $consulta = "SELECT * FROM canchas
                INNER JOIN reservas ON reservas.id = canchas.reserva_id
                WHERE reservas.id NOT IN (
                SELECT reservas.id FROM instancia_reservas
                INNER JOIN reservas ON reservas.id = instancia_reservas.reserva_id
                WHERE instancia_reservas.fecha_reserva = ? AND instancia_reservas.bloque_id = ?)";

            $canchasDisponible=DB::select($consulta, [$fecha_reserva, $id_bloque]);
            $datos = ["canchasDisponible" => $canchasDisponible, 'id_bloque' => $id_bloque, 'fecha_reserva' => $fecha_reserva];    
            return redirect()->route('cancha_reservar_filtrado')->with('datos', $datos);
        } catch (\Throwable $th) {
            return back()->with('error', 'Salió mal');
        }
         
    }

    public function post_reservar_filtrado(Request $request){
        try{
            $id_usuario= Auth::user()->id;
            $id_bloque=$request->input('bloque');
            $id_cancha = $request->input('seleccionCancha');
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

            return redirect()->route('cancha_reservar');
        }catch (\Throwable $th){
            return back()->with('error', '¡Hubo un error al reservar!');
        }
    }
}
