<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Reservas\Throwable;
use App\Http\Requests\Reserva\SalaGimnasioRequest;
use App\Models\Bloques;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class SalaGimnasioController extends Controller
{
    //
    public function post_registrar(SalaGimnasioRequest $request){
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

            DB::table("sala_gimnasios")->insert([
                "reserva_id" => $id_reserva,
                "capacidad" => $request->capacidad,
            ]);
            return back()->with("success","Sala Gimnasio registrada correctamente");
        } catch (\Throwable $th) {
            return back()->with('error', '¡Hubo un error al guardar el registro!');
        }
    }

    public function get_reservar(){
        $bloquesDisponibles = Bloques::all();
        return view('salagimnasio.reservar',compact('bloquesDisponibles'));
    }


    public function get_registrar(){

        $ubicacionesDeportivas = Ubicacion::where('categoria', 'deportivo')->whereNotIn('nombre_ubicacion',['aire libre'])->get();

        return view('salagimnasio.registrar', compact('ubicacionesDeportivas'));
    }

    public function get_reservar_filtrado(){
        try {
            $datos = session('datos');
            $salasGimnasioDisponible = $datos['salasGimnasioDisponible'];
            $id_bloque = $datos['id_bloque'];
            $fecha_reserva = $datos['fecha_reserva'];
            return view('salagimnasio.reservar_filtrado', compact('salasGimnasioDisponible', 'id_bloque', 'fecha_reserva'));

        } catch (\Throwable $th) {
            //throw $th;
            // return back()->with('error', 'Salió mal');
            return redirect()->route('salagimnasio_reservar');
        }


    }

    public function post_reservar(Request $request){
        try {

            $id_bloque=$request->input('bloques');

            $fecha_reserva=$request->input('fecha');

            $consulta= "SELECT * FROM sala_gimnasios
                INNER JOIN reservas ON reservas.id = sala_gimnasios.reserva_id
                INNER JOIN ubicaciones ON reservas.ubicacione_id = ubicaciones.id
                AND reserva_id NOT IN (
                SELECT reservas.id
                FROM instancia_reservas
                INNER JOIN reservas ON instancia_reservas.reserva_id = reservas.id
                INNER JOIN sala_gimnasios ON instancia_reservas.reserva_id = sala_gimnasios.reserva_id
                WHERE instancia_reservas.fecha_reserva=? AND instancia_reservas.bloque_id=? AND sala_gimnasios.capacidad <= (
                SELECT COUNT(*)
                FROM instancia_reservas ir
                WHERE ir.fecha_reserva = instancia_reservas.fecha_reserva
                AND ir.reserva_id = instancia_reservas.reserva_id
                AND ir.bloque_id = instancia_reservas.bloque_id
                )
                GROUP BY reservas.id
                )
            ";

            $salasGimnasioDisponible=DB::select($consulta, [$fecha_reserva, $id_bloque]);
            $datos = ["salasGimnasioDisponible" => $salasGimnasioDisponible, 'id_bloque' => $id_bloque, 'fecha_reserva' => $fecha_reserva];
            return redirect()->route('salagimnasio_reservar_filtrado')->with('datos', $datos);
        } catch (\Throwable $th) {
            return back()->with('error', '¡Hubo un error al reservar!');
        }
    }
    public function post_reservar_filtrado(Request $request){
        try{
            $id_usuario= Auth::user()->id;
            $id_bloque=$request->input('bloque');
            $id_cancha = $request->input('seleccionSala');
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

            DB::table("historial_instancia_reservas")->insert([
                "fecha_reserva"=>$fecha_reserva,
                "user_id"=>$id_usuario,
                "bloque_id"=>$id_bloque,
                "reserva_id"=>$id_estado_instancia,      //ESTA ES EL DÍA EN QUE SER RESERVÓ
            ]);
            return redirect()->route('salagimnasio_reservar');
        }catch (\Throwable $th){
            return back()->with('error', '¡Hubo un error al reservar!');
        }
    }
}
