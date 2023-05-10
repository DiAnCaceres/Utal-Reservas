<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reserva\CanchaRequest;
use App\Models\Bloques;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

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
            $id_bloque=$request->input('bloques');

            //OBTENER FECHA DE LA RESERVA
            $fecha_reserva=$request->input('fecha');

            $comprobacion = "
                SELECT * FROM instancia_reservas
                INNER JOIN reservas ON reservas.id = instancia_reservas.reserva_id
                WHERE user_id=? AND fecha_reserva=? AND bloque_id=?
            ";
            $registrosUsuario=DB::select($comprobacion,[$id_usuario,$fecha_reserva,$id_bloque]);
            $cantidadReservas=count($registrosUsuario);

            if($cantidadReservas>0){
                $nombre_reserva = $registrosUsuario[0]->nombre;
                return redirect()->route('cancha_reservar')->with('error', "Existe una reserva para el mismo día y el mismo bloque.");
            }else{
                $consulta = "SELECT * FROM canchas
                INNER JOIN reservas ON reservas.id = canchas.reserva_id
                INNER JOIN ubicaciones ON ubicaciones.id = reservas.ubicacione_id
                WHERE reservas.id NOT IN (
                SELECT reservas.id FROM instancia_reservas
                INNER JOIN reservas ON reservas.id = instancia_reservas.reserva_id
                WHERE instancia_reservas.fecha_reserva = ? AND instancia_reservas.bloque_id = ?)";

                $canchasDisponible=DB::select($consulta, [$fecha_reserva, $id_bloque]);
                $datos = ["canchasDisponible" => $canchasDisponible, 'id_bloque' => $id_bloque, 'fecha_reserva' => $fecha_reserva];
                return redirect()->route('cancha_reservar_filtrado')->with('datos', $datos);
            }

        } catch (\Throwable $th) {
            return back()->with('error', 'Salió mal');
        }

    }

    public function post_reservar_filtrado(Request $request){
        try{
            $id_usuario= Auth::user()->id;
            $id_bloque=$request->input('bloque');
            $id_cancha = $request->input('seleccionCancha');
            $bloque_sgte = $request->input('bloque_sgte');
            $fecha_reserva=$request->input('fecha');

            //RESERVA PRIMERA SELECCIONADA
            $existeRegistro = DB::table("instancia_reservas")->whereDate('fecha_reserva', $fecha_reserva)
                    ->where('reserva_id', $id_cancha)
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
                            "reserva_id" => $id_cancha,
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
                            "reserva_id"=>$id_cancha,
                            "fecha_estado"=>$date,
                            "estado_instancia_id"=>$id_estado_instancia
                        ]);

                        //SI MARCA QUE QUIERE EL BLOQUE SIGUIENTE DEBE REGISTRAR Y VERIFICAR QUE NO EXISTA OTRA RESERVA
                        if ($bloque_sgte and $id_bloque!=12){
                            $bloque_siguiente = $id_bloque + 1;

                            //PRIMERO VERIFICAMOS QUE NO EXISTA OTRA RESERVA EN EL BLOQUE SGTE
                            $existeRegistroSgte = DB::table("instancia_reservas")->whereDate('fecha_reserva', $fecha_reserva)
                                ->where('reserva_id', $id_cancha)
                                ->where('bloque_id', $bloque_siguiente)
                                ->doesntExist();

                            if ($existeRegistroSgte) {
                                $numReservas = DB::table("instancia_reservas")->where('user_id', $id_usuario)
                                        ->whereDate('fecha_reserva', $fecha_reserva)
                                        ->count();
                                // Verificamos si el número de reservas es menor o igual a 2
                                if ($numReservas < 2) {
                                    // El estudiante tiene menos de dos reservas para la fecha indicada, puedes proceder a hacer la reserva
                                    DB::table("instancia_reservas")->insert([
                                        "fecha_reserva" => $fecha_reserva,
                                        "reserva_id" => $id_cancha,
                                        "user_id" => $id_usuario,
                                        "bloque_id" => $bloque_siguiente,
                                    ]);
                                    $estado_instancia_reserva = DB::table("estado_instancias")->where('nombre_estado', "reservado")->first();


                                    //AHORA AGREGAMOS AL HISTORIAL DE RESERVAS
                                    $id_estado_instancia = $estado_instancia_reserva->id;
                                    $date = Carbon::now();
                                    $date = $date->format('Y-m-d');
                                    DB::table("historial_instancia_reservas")->insert([
                                        "fecha_reserva"=>$fecha_reserva,
                                        "user_id"=>$id_usuario,
                                        "bloque_id"=>$bloque_siguiente,
                                        "reserva_id"=>$id_cancha,
                                        "fecha_estado"=>$date,
                                        "estado_instancia_id"=>$id_estado_instancia
                                    ]);
                                } else {
                                    return redirect()->route('cancha_reservar')->with('error','Se reservó la primera, pero tienes dos reservas en la misma fecha, no puedes reservar más hasta otro día.');
                                }
                            } else {
                                return redirect()->route('cancha_reservar')->with('error','Primera reserva hecha, pero ya existe otra reserva en el siguiente bloque.');
                            }
                        }
                    } else {
                        return redirect()->route('cancha_reservar')->with('error','Ya tienes dos reservas en la misma fecha, no puedes reservar más hasta otro día.');
                    }
            } else {
                return redirect()->route('cancha_reservar')->with('error','Ya realizaste esta misma reserva');
            }



            //SE REALIZÓ LA RESERVA CORRECTAMENTE
            return redirect()->route('cancha_reservar')->with("success","Cancha registrada correctamente");
        }catch (\Throwable $th){
            return back()->with('error', '¡Hubo un error al reservar!');
        }
    }

    /* ---------------------------------- SEMANA 4 ----------------------------------------*/


     /* ----------------------- RU019: Cancelar ---------------------------------*/
     public function get_cancelar(){
        $user_id=Auth::user()->id;
        $reservas="SELECT * FROM historial_instancia_reservas as h
        INNER JOIN reservas as r ON r.id = h.reserva_id
        INNER JOIN bloques as b ON b.id = h.bloque_id
        INNER JOIN canchas as se ON se.reserva_id = r.id
        WHERE h.estado_instancia_id=1 AND
        h.user_id=?";
        $resultados=DB::select($reservas,[$user_id]);
    
         // Ejecutar la consulta y pasar el parámetro del usuario
        return view('cancha.cancelar', ['reservas' => $resultados]);
        /*return($reservas);*/
    }

    public function post_cancelar(Request $request){
    $resultadosSeleccionados = $request->input('a_cancelar');
        foreach ($resultadosSeleccionados as $resultadoSeleccionado) {
        list($fecha_reserva, $bloque_id, $reserva_id, $user_id) = explode('|', $resultadoSeleccionado);
        $date = Carbon::now();
        $date = $date->format('Y-m-d');
        DB::table("historial_instancia_reservas")->insert([
            "fecha_reserva"=>$fecha_reserva,
            "bloque_id"=>$bloque_id,
            "user_id"=>$user_id,
            "reserva_id"=>$reserva_id,
            "fecha_estado"=>$date,
            "estado_instancia_id"=>5
        ]);
    }
        return redirect()->route('cancha_cancelar');//->with('datos', $datos);

    }
     /* ----------------------- RU20: Entregar---------------------------------*/

    public function get_entregar(){
        $resultados="";
        $mostrarResultados=false;
        return view('cancha.entregar',compact('resultados','mostrarResultados'));
    }

    public function post_entregar(Request $request){
        $mostrarResultados=true;
        $resultados="super8 genios superdotados"; // ejemplo
        return view('cancha.entregar',compact('resultados','mostrarResultados'));
    }

    public function post_entregar_resultados(Request $request){
         return redirect()->route('cancha_entregar_filtrado');//->with('datos', $datos);
    }


    public function get_entregar_filtrado(){
        return view('cancha.entregar_filtrado');
    }

    public function post_entregar_filtrado(Request $request){
        return redirect()->route('cancha_entregar');//->with('datos', $datos);
    }

}
