<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reserva\ImplementoRequest;
use App\Models\Bloques;
use App\Models\Implemento;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\DB;

class ImplementoController extends Controller
{
    // atributos para la reserva
    private $id_bloque;
    private $fecha_reserva;
    private $id_usuario;
    private $id_cancha;

    //
    public function post_registrar(ImplementoRequest $request){
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

        return view('implemento.reservar_filtrado');
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

    public function post_reservar(ImplementoRequest $request){


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

    public function post_reservar_filtrado(Request $request){

    }
}