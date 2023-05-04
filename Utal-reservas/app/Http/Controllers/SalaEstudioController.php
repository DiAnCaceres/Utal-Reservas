<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Reservas\Throwable;
use App\Http\Requests\Reserva\SalaEstudioRequest;
use App\Models\Bloques;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalaEstudioController extends Controller
{
    public $id_bloque;
    public $fecha_reserva;
    //
    public function post_registrar(SalaEstudioRequest $request){
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

            DB::table("sala_estudios")->insert([
                "reserva_id" => $id_reserva,
                "capacidad" => $request->capacidad,
            ]);
            return back()->with("success","Sala Estudio registrada correctamente");
        } catch (\Throwable $th) {
            return back()->with('error', '¡Hubo un error al guardar el registro!');
        }
    }

    public function get_registrar(){
        $ubicacionesEstudio = Ubicacion::where('categoria', 'educativo')->get();
        return view('salaestudio.registrar', compact('ubicacionesEstudio'));
    }

    public function get_reservar(){
        $bloquesDisponibles = Bloques::all();
        return view('salaestudio.reservar',compact('bloquesDisponibles'));
    }

    public function get_reservar_filtrado(){

        try {
            $datos = session('datos');
            $salasEstudioDisponible = $datos['salasEstudioDisponible'];
            $id_bloque = $datos['id_bloque'];
            $fecha_reserva = $datos['fecha_reserva'];
            return view('salaestudio.reservar_filtrado', compact('salasEstudioDisponible', 'id_bloque', 'fecha_reserva'));

        } catch (\Throwable $th) {
            //throw $th;
            // return back()->with('error', 'Salió mal');
        }

        return redirect()->route('salaestudio_reservar');

    }

    public function post_reservar(Request $request){

        try {
            //OBTENGO EL ID DEL BLOQUE QUE SE SELECIONÓ
            $bloque=$request->bloques;
            $id_bloque = DB::table("bloques")->find($bloque);
            $id_bloque = $id_bloque->id;

            //OBTENER FECHA DE LA RESERVA
            $fecha_reserva=$request->fecha;

            $consulta = "SELECT * FROM sala_estudios
                INNER JOIN reservas ON reservas.id = sala_estudios.reserva_id
                INNER JOIN ubicaciones ON reservas.ubicacione_id = ubicaciones.id
                WHERE reservas.id NOT IN (
                SELECT reservas.id FROM instancia_reservas
                INNER JOIN reservas ON reservas.id = instancia_reservas.reserva_id
                WHERE instancia_reservas.fecha_reserva = ? AND instancia_reservas.bloque_id = ?)";

            $salasEstudioDisponible=DB::select($consulta, [$fecha_reserva, $id_bloque]);
            $datos = ["salasEstudioDisponible" => $salasEstudioDisponible, 'id_bloque' => $id_bloque, 'fecha_reserva' => $fecha_reserva];
            return redirect()->route('salaestudio_reservar_filtrado')->with('datos', $datos);
        } catch (\Throwable $th) {
            return back()->with('error', 'Salió mal');
        }

    }
    public function post_reservar_filtrado(Request $request){
        try{
            $id_usuario= Auth::user()->id;
            $id_bloque=$request->input('bloque');
            $id_sala_estudio = $request->input('seleccionSala');
            // $sala_estudio = DB::table("reservas")->find($id_sala_estudio); //Busco el registro
            $fecha_reserva=$request->input('fecha');
            DB::table("instancia_reservas")->insert([
                "fecha_reserva" => $fecha_reserva,
                "reserva_id" => $id_sala_estudio,
                "user_id" => $id_usuario,
                "bloque_id" => $id_bloque,
            ]);

            $estado_instancia_reserva = DB::table("estado_instancia_reservas")->where('nombre_estado', "reservado")->first();
            $id_estado_instancia = $estado_instancia_reserva->id;

            DB::table("historial_instancia_reservas")->insert([
                "fecha_reserva"=>$fecha_reserva,
                "user_id"=>$id_usuario,
                "bloque_id"=>$id_bloque,
                "reserva_id"=>$id_estado_instancia,
            ]);

            return redirect()->route('salaestudio_reservar');
        }catch (\Throwable $th){
            return back()->with('error', '¡Hubo un error al reservar!');
        }
    }
}
