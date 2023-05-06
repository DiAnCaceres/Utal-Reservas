<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Reservas\Throwable;
use App\Http\Requests\Reserva\SalaEstudioRequest;
use App\Models\Bloques;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

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
            //VALIDAR ENTRADAS
            $validator = Validator::make($request->all(), [
                'fecha' => 'required|date'
            ]);
            $validator->messages()->add('fecha.required', 'Fecha es requerido');
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $id_usuario= Auth::user()->id;
            //OBTENGO EL ID DEL BLOQUE QUE SE SELECIONÓ
            $bloque=$request->bloques;
            $id_bloque = DB::table("bloques")->find($bloque);
            $id_bloque = $id_bloque->id;
            //OBTENER FECHA DE LA RESERVA
            $fecha_reserva=$request->fecha;

            $comprobacion = "
                SELECT * FROM instancia_reservas 
                INNER JOIN reservas ON reservas.id = instancia_reservas.reserva_id
                WHERE user_id=? AND fecha_reserva=? AND bloque_id=?
            ";
            $registrosUsuario=DB::select($comprobacion,[$id_usuario,$fecha_reserva,$id_bloque]);
            $cantidadReservas=count($registrosUsuario);

            if($cantidadReservas>0){
                $nombre_reserva = $registrosUsuario[0]->nombre;
                return redirect()->route('salaestudio_reservar')->with('error', "Tienes una reserva para el mismo día y el mismo bloque, especificamente reservaste: $nombre_reserva. NO PUEDES RESERVAR DOS SERVICIOS EN UN MISMO BLOQUE Y FECHA.");
            }else{
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
            }
        
            
        } catch (\Throwable $th) {
            return back()->with('error', 'Salió mal');
        }

    }
    public function post_reservar_filtrado(Request $request){
        try{
            $id_usuario= Auth::user()->id;
            $id_bloque=$request->input('bloque');
            $id_sala_estudio = $request->input('seleccionSala');
            $fecha_reserva=$request->input('fecha');
            
            $existeRegistro = DB::table("instancia_reservas")->whereDate('fecha_reserva', $fecha_reserva)
                    ->where('reserva_id', $id_sala_estudio)
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
                            "reserva_id" => $id_sala_estudio,
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
                            "reserva_id"=>$id_sala_estudio,
                            "fecha_estado"=>$date,
                            "estado_instancia_id"=>$id_estado_instancia
                        ]);
                    } else {
                        return redirect()->route('salaestudio_reservar')->with('error','Ya tienes dos reservas en la misma fecha, no puedes reservar más hasta otro día.');
                    }
            } else {
                return redirect()->route('salaestudio_reservar')->with('error','Ya realizaste esta misma reserva');
            }
            return redirect()->route('salaestudio_reservar');
        }catch (\Throwable $th){
            return back()->with('error', '¡Hubo un error al reservar!');
        }
    }

    /* ---------------------------------- SEMANA 4 ----------------------------------------*/

   
     /* ----------------------- RU07: Cancelar ---------------------------------*/
    public function get_cancelar(){
        return view('salaestudio.cancelar');
    }

    public function post_cancelar(Request $request){
        return redirect()->route('salaestudio_cancelar');//->with('datos', $datos);
    }

     /* ----------------------- RU08: Entregar---------------------------------*/

    public function get_entregar(){
        return view('salaestudio.entregar');
    }

    public function post_entregar(Request $request){
        return redirect()->route('salaestudio_entregar_filtrado');//->with('datos', $datos);
    }


    public function get_entregar_filtrado(){
        return view('salaestudio.entregar_filtrado');
    }

    public function post_entregar_filtrado(Request $request){
        return redirect()->route('salaestudio_entregar_filtrado');//->with('datos', $datos);
    }


    /* ----------------------- RU09: Recepcionar--------------------------------*/
    public function get_recepcionar(){
        return view('salaestudio.recepcionar');
    }  

    public function post_recepcionar(Request $request){
       return redirect()->route('salaestudio_recepcionar_filtrado');//->with('datos', $datos);
    }


    public function get_recepcionar_filtrado(){
        return view('salaestudio.recepcionar_filtrado');
    }

    public function post_recepcionar_filtrado(Request $request){
        return redirect()->route('salaestudio_recepcionar_filtrado');//->with('datos', $datos);
    }

}
