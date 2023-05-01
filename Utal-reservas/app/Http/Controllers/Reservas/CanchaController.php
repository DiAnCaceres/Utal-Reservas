<?php

namespace App\Http\Controllers\Reservas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reserva\CanchaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CanchaController extends Controller
{   
    // Atributos para la reserva
    private $id_bloque;
    private $fecha_reserva;
    private $id_usuario;
    private $id_cancha;

    //
    public function store(CanchaRequest $request){
        $sql=true;
        try {
            //OBTENGO EL ID DE LA UBICACION QUE SE SELECIONÓ
            $nom_ubi=$request->nombre_ubicacion;
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

    public function reservar(CanchaRequest $request) {

        try {
            
            //OBTENGO EL ID DEL BLOQUE QUE SE SELECIONÓ
            $this->id_bloque=$request->bloque->id;

            //OBTENER EL ESTUDIANTE
            $this->id_usuario=$request->user()->id;

            //OBTENER FECHA DE LA RESERVA
            $this->fecha_reserva=$request->fecha;

            //OBTENER ID DE LA RESERVA
            $this->id_cancha = $request->cancha->id;

            DB::table("instancia_reservas")->insert([
                "bloque_id" => $this->id_bloque, 
                "user_id" => $this->id_usuario,
                "fecha_reserva" => $this->fecha_reserva,
                "reserva_id" => $this->id_cancha, 
            ]);

            $estado_instancia_reserva = DB::table("estado_instancia_reservas")->where('nombre_estado', 'reservado')->first();

            $id_estado_instancia = $estado_instancia_reserva->id;

            DB::table("historial_reservas")->insert([
                "instancia_reserva_fecha_reserva"=>$this->fecha_reserva,
                "instancia_reserva_user_id"=>$this->id_usuario,
                "instancia_reserva_bloque_id"=>$this->id_bloque,
                "estado_instancia_reserva_id"=>$id_estado_instancia,
                "fecha"=>date('Y-m-d')      //ESTA ES EL DÍA EN QUE SER RESERVÓ
            ]);

            return back()->with('success', "Reserva de cancha registrada correctamente");
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', '¡Hubo un error al reservar!');
        }

    }

    public function canchas_disponibles(CanchaRequest $request){

        // $this->reservar($request);

    }
}
