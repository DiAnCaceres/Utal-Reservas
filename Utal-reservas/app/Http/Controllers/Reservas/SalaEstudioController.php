<?php

namespace App\Http\Controllers\Reservas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reserva\SalaEstudioRequest;
use Error;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaEstudioController extends Controller
{
    public $id_bloque;
    public $fecha_reserva;
    //

    public function __construct()
    {
        $this->id_bloque = null;
        $this->fecha_reserva = null;
    }

    public function store(SalaEstudioRequest $request){
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
    public function reservar(Request $request){
        try {
            //OBTENGO EL ID DEL BLOQUE QUE SE SELECIONÓ
            $bloque=$request->bloques;
            $id_bloque = DB::table("bloques")->find($bloque);
            $id_bloque = $id_bloque->id;

            //OBTENER FECHA DE LA RESERVA
            $fecha_reserva=$request->fecha;

            $consulta = "SELECT * FROM sala_estudios
             INNER JOIN reservas ON reservas.id = sala_estudios.reserva_id
             WHERE reservas.id NOT IN (
             SELECT reservas.id FROM instancia_reservas
             INNER JOIN reservas ON reservas.id = instancia_reservas.reserva_id
             WHERE instancia_reservas.fecha_reserva = ? AND instancia_reservas.bloque_id = ?)";

            $salasEstudioDisponible=DB::select($consulta, [$fecha_reserva, $id_bloque]);

            return view('reservar.reservarDisponible.sala_estudio_disponible',compact('salasEstudioDisponible', 'id_bloque', 'fecha_reserva'));

        } catch (\Throwable $th) {
            return back()->with('error', '¡Hubo un error al reservar!');
        }
    }
    public function disponibilidad(Request $request){
        try{
            $id_usuario=3;
            $id_bloque=$request->input('bloque');
            $id_sala_estudio = $request->seleccionSala;
            $sala_estudio = DB::table("reservas")->find($id_sala_estudio); //Busco el registro
            $fecha_reserva=$request->input('fecha');
            DB::table("instancia_reservas")->insert([
                "fecha_reserva" => $fecha_reserva,
                "reserva_id" => $sala_estudio->id,
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

            return back()->with("success","Sala Estudio reservada correctamente");
        }catch (\Throwable $th){
            return back()->with('error', '¡Hubo un error al reservar!');
        }
    }
}
