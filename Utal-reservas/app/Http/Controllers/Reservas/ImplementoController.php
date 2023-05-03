<?php

namespace App\Http\Controllers\Reservas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reserva\ImplementoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Ubicacion;

class ImplementoController extends Controller
{
    private $id_bloque;
    private $fecha_reserva;
    //
    public function store(ImplementoRequest $request){
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
    public function implemento(){
        $ubicacionesDeportivas = Ubicacion::where('categoria', 'deportivo')->whereNotIn('nombre_ubicacion',['aire libre'])->get();
        $id_bloque=1;
        return view('registro.registrar_implemento', compact('ubicacionesDeportivas'));
    }
    public function reservar(Request $request){
        try {
            
            $id_bloque=1;

            $fecha_reserva="2023-07-13";

            $id_implemento =1;
            
            $implementoDisponible=DB::select("
                SELECT * FROM implementos
                INNER JOIN reservas ON reservas.id = implementos.reserva_id
                WHERE reservas.id NOT IN (
                    SELECT reservas.id FROM instancia_reservas
                    INNER JOIN reservas ON reservas.id = instancia_reservas.reserva_id
                    WHERE instancia_reservas.fecha_reserva='1111-11-11' AND instancia_reservas.bloque_id=1
                )
            ");
            return view('reservar.reservarDisponible.implemento_disponible',compact('implementoDisponible'));
        } catch (Throwable $th) {
            return back()->with('error', '¡Hubo un error al reservar!');
        }
    }
    public function disponibilidad(){
        try{
            $id_usuario=1;
            $id_bloque=1;
            $id_implemento=2;
            $fecha_reserva="2023-01-05";
            DB::table("instancia_reservas")->insert([
                "bloque_id" => $this->id_bloque,
                "user_id" => $id_usuario,
                "fecha_reserva" => $fecha_reserva,
                "reserva_id" => $id_implemento,
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
        }catch (Throwable $th){

        }
    }


}
