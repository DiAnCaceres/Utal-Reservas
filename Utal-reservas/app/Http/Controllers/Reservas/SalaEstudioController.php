<?php

namespace App\Http\Controllers\Reservas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reserva\SalaEstudioRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaEstudioController extends Controller
{
    private $id_bloque;
    private $fecha_reserva;
    //
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
            $id_bloque=$request->bloque->id;
            //$id_bloque=1;

            //OBTENER EL ESTUDIANTE
            $id_usuario=$request->user()->id;
            //$id_usuario=2;

            //OBTENER FECHA DE LA RESERVA
            $fecha_reserva=$request->fecha;
            //$fecha_reserva="2023-07-13";

            //OBTENGO EL ID DE LA UBICACION QUE SE SELECIONÓ

            //OBTENER ID DE LA RESERVA
            // $id_sala_estudio = $request->sala->id;
            $id_sala_estudio =1;
            //CREAR EL REGISTRO
            // DB::table("instancia_reservas")->insert([
            //     "bloque_id" => 3,
            //     "user_id" => $id_usuario,
            //     "fecha_reserva" => $fecha_reserva,
            //     "reserva_id" => $id_sala_estudio,
            // ]);

            // $estado_instancia_reserva = DB::table("estado_instancia_reserva")->where('nombre_estado', "reservado")->first();
            // $id_estado_instancia = $estado_instancia_reserva->id;

            // DB::table("historial_reservas")->insert([
            //     "instancia_reserva_fecha_reserva"=>$fecha_reserva,
            //     "instancia_reserva_user_id"=>$id_usuario,
            //     "instancia_reserva_bloque_id"=>3,
            //     "estado_instancia_reserva_id"=>$id_estado_instancia,
            //     "fecha"=>date('Y-m-d')      //ESTA ES EL DÍA EN QUE SER RESERVÓ
            // ]);
            // return back()->with("success","Reserva de Sala Gimnasio registrada correctamente");
            return view('reservar.reservarDisponible.sala_estudio_disponible')->with(['fecha_reserva' => $fecha_reserva,'id_usuario'=>$id_usuario,'id_bloque'=>$fecha_reserva]);
        } catch (\Throwable $th) {
            return back()->with('error', '¡Hubo un error al reservar!');
        }
    }
    public function disponibilidad(){
        try{
            $id_usuario=1;
            $id_bloque=1;
            $id_sala_estudio=2;
            $fecha_reserva="2023-01-05";
            DB::table("instancia_reservas")->insert([
                "bloque_id" => $this->id_bloque,
                "user_id" => $id_usuario,
                "fecha_reserva" => $fecha_reserva,
                "reserva_id" => $id_sala_estudio,
            ]);

            $estado_instancia_reserva = DB::table("estado_instancia_reserva")->where('nombre_estado', "reservado")->first();
            $id_estado_instancia = $estado_instancia_reserva->id;

            DB::table("historial_reservas")->insert([
                "instancia_reserva_fecha_reserva"=>$fecha_reserva,
                "instancia_reserva_user_id"=>$id_usuario,
                "instancia_reserva_bloque_id"=>$this->id_bloque,
                "estado_instancia_reserva_id"=>$id_estado_instancia,
                "fecha"=>date('Y-m-d')      //ESTA ES EL DÍA EN QUE SER RESERVÓ
            ]);
        }catch (\Throwable $th){

        }
    }
}
