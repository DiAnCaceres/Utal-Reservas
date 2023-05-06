<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Reservas\Throwable;
use App\Http\Requests\Reserva\SalaGimnasioRequest;
use App\Models\Bloques;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

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
            //VALIDAR ENTRADAS
            $validator = Validator::make($request->all(), [
                'fecha' => 'required|date'
            ]);
            $validator->messages()->add('fecha.required', 'Fecha es requerido');
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $id_usuario= Auth::user()->id;
            $id_bloque=$request->input('bloques');

            $array = json_decode($id_bloque, true);
            $id_bloque_comprobacion = $array['id'];
            $fecha_reserva=$request->input('fecha');

            $comprobacion = "
                SELECT * FROM instancia_reservas 
                INNER JOIN reservas ON reservas.id = instancia_reservas.reserva_id
                WHERE user_id=? AND fecha_reserva=? AND bloque_id=?
            ";
  
            $registrosUsuario=DB::select($comprobacion,[$id_usuario,$fecha_reserva,$id_bloque_comprobacion]);
            $cantidadReservas=count($registrosUsuario);

            if($cantidadReservas>0){
                $nombre_reserva = $registrosUsuario[0]->nombre;
                return redirect()->route('salagimnasio_reservar')->with('error', "Tienes una reserva para el mismo día y el mismo bloque, especificamente reservaste: $nombre_reserva. NO PUEDES RESERVAR DOS SERVICIOS EN UN MISMO BLOQUE Y FECHA.");
            }else{
                $consulta= "
                    SELECT * FROM sala_gimnasios
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
            
            }
            
        } catch (\Throwable $th) {
            return back()->with('error', '¡Hubo un error al reservar!');
        }
    }
    public function post_reservar_filtrado(Request $request){
        try{
            $id_usuario= Auth::user()->id;
            $id_bloque=$request->input('bloque');
            $id_bloque=json_decode($id_bloque);
            $id_bloque=$id_bloque->id;
            $id_gimnasio = $request->input('seleccionSala');
            $fecha_reserva=$request->input('fecha');
            
            $existeRegistro = DB::table("instancia_reservas")->whereDate('fecha_reserva', $fecha_reserva)
                    ->where('reserva_id', $id_gimnasio)
                    ->where('user_id', $id_usuario)
                    ->where('bloque_id', $id_bloque)
                    ->doesntExist();

            if ($existeRegistro) {
                $numReservas = DB::table("instancia_reservas")->where('user_id', $id_usuario)
                        ->whereDate('fecha_reserva', $fecha_reserva)
                        ->count();

                    // Verificamos si el número de reservas es menor o igual a 2
                    if ($numReservas < 2) {
                        // El estudiante tiene menos de dos reservas para la fecha indicada, puedes proceder a hacer la reserva
                        DB::table("instancia_reservas")->insert([
                            "fecha_reserva" => $fecha_reserva,
                            "reserva_id" => $id_gimnasio,
                            "user_id" => $id_usuario,
                            "bloque_id" => $id_bloque,
                        ]);
                        $estado_instancia_reserva = DB::table("estado_instancias")->where('nombre_estado', "reservado")->first();


                        //AHORA AGREGAMOS AL HISTORIAL DE RESERVAS
                        $id_estado_instancia = $estado_instancia_reserva->id;
                        $date = Carbon::now();
                        $date = $date->format('Y-m-d');
                        DB::table("historial_instancia_reservas")->insert([
                            "fecha_reserva"=>$fecha_reserva,
                            "user_id"=>$id_usuario,
                            "bloque_id"=>$id_bloque,
                            "reserva_id"=>$id_gimnasio,
                            "fecha_estado"=>$date,
                            "estado_instancia_id"=>$id_estado_instancia
                        ]);
                    } else {
                        return redirect()->route('salagimnasio_reservar')->with('error','Ya tienes dos reservas en la misma fecha, no puedes reservar más hasta otro día.');
                    }
            } else {
                return redirect()->route('salagimnasio_reservar')->with('error','Ya realizaste esta misma reserva');
            }
            //SE REALIZÓ LA RESERVA CORRECTAMENTE
            return redirect()->route('salagimnasio_reservar');
        }catch (\Throwable $th){
            return back()->with('error', '¡Hubo un error al reservar!');
        }
    }

    /* ---------------------------------- SEMANA 4 ----------------------------------------*/

   
     /* ----------------------- RU13: Cancelar ---------------------------------*/
    public function get_cancelar(){
        return view('salagimnasio.cancelar');
    }

    public function post_cancelar(Request $request){
        return redirect()->route('salagimnasio_cancelar');//->with('datos', $datos);
    }

     /* ----------------------- RU14: Entregar---------------------------------*/

    public function get_entregar(){
        return view('salagimnasio.entregar');
    }

    public function post_entregar(Request $request){
        return redirect()->route('salagimnasio_entregar_filtrado');//->with('datos', $datos);
    }


    public function get_entregar_filtrado(){
        return view('salagimnasio.entregar_filtrado');
    }

    public function post_entregar_filtrado(Request $request){
        return redirect()->route('salagimnasio_entregar');//->with('datos', $datos);
    }


    /* ----------------------- RU10: Recepcionar--------------------------------*/
     public function get_recepcionar(){
        return view('salagimnasio.recepcionar');
    }  

    public function post_recepcionar(Request $request){
       return redirect()->route('salagimnasio_recepcionar_filtrado');//->with('datos', $datos);
    }


    public function get_recepcionar_filtrado(){
        return view('salagimnasio.recepcionar_filtrado');
    }

    public function post_recepcionar_filtrado(Request $request){
        return redirect()->route('salagimnasio_recepcionar_filtrado');//->with('datos', $datos);
    }

}
