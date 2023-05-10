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
                'fecha' => 'required|date|after_or_equal:today'
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

            // Compruebo si se selecciona un bloque horario válido para el día de hoy
            $fecha_actual = date('Y-m-d');
            if($fecha_actual == $fecha_reserva){
                $hora_actual = Carbon::now()->format('H:i:s');
                $bloque = DB::table('bloques')->where('id', $id_bloque)->first();
                
                if($hora_actual>$bloque->hora_inicio){
                    return back()->withErrors(['bloque' => 'La hora seleccionada no es válida.']);
                }
                
            }

            $comprobacion = "
                SELECT * FROM instancia_reservas
                INNER JOIN reservas ON reservas.id = instancia_reservas.reserva_id
                WHERE user_id=? AND fecha_reserva=? AND bloque_id=?
            ";
            $registrosUsuario=DB::select($comprobacion,[$id_usuario,$fecha_reserva,$id_bloque]);
            $cantidadReservas=count($registrosUsuario);

            if($cantidadReservas>0){
                $nombre_reserva = $registrosUsuario[0]->nombre;
                return redirect()->route('salaestudio_reservar')->with('error', "Existe una reserva para el mismo día y el mismo bloque.");
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
            $bloque_sgte = $request->input('bloque_sgte');
            $fecha_reserva=$request->input('fecha');

            //RESERVA PRIMERA SELECCIONADA
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
                        //SI MARCA QUE QUIERE EL BLOQUE SIGUIENTE DEBE REGISTRAR Y VERIFICAR QUE NO EXISTA OTRA RESERVA
                        if ($bloque_sgte){
                            $bloque_siguiente = $id_bloque + 1;

                            //PRIMERO VERIFICAMOS QUE NO EXISTA OTRA RESERVA EN EL BLOQUE SGTE
                            $existeRegistroSgte = DB::table("instancia_reservas")->whereDate('fecha_reserva', $fecha_reserva)
                                ->where('reserva_id', $id_sala_estudio)
                                ->where('bloque_id', $bloque_siguiente)
                                ->doesntExist();

                            if ($existeRegistroSgte and $id_bloque!=12) {
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
                                        "reserva_id"=>$id_sala_estudio,
                                        "fecha_estado"=>$date,
                                        "estado_instancia_id"=>$id_estado_instancia
                                    ]);
                                } else {
                                    return redirect()->route('salaestudio_reservar')->with('error','Se reservó la primera, pero tienes dos reservas en la misma fecha, no puedes reservar más hasta otro día.');
                                }
                            } else {
                                return redirect()->route('salaestudio_reservar')->with('error','Primera reserva hecha, pero ya existe otra reserva en el siguiente bloque.');
                            }
                        }
                    } else {
                        return redirect()->route('salaestudio_reservar')->with('error','Ya tienes dos reservas en la misma fecha, no puedes reservar más hasta otro día.');
                    }
            } else {
                return redirect()->route('salaestudio_reservar')->with('error','Ya realizaste esta misma reserva');
            }
            return redirect()->route('salaestudio_reservar')->with("success","Sala Estudio reservada correctamente");
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
        $resultados="";
        $mostrarResultados=false;
        return view('salaestudio.entregar',compact('resultados','mostrarResultados'));
    }

    public function post_entregar(Request $request){
        $mostrarResultados=true;
         // ejemplo

        try {
            $rut = $request->input('rut');

            $validator = Validator::make($request->all(), [
                'rut' => ['required', 'regex:/^[0-9]{1,2}\.[0-9]{3}\.[0-9]{3}-[0-9kK]{1}$/'],
            ]);

            $validator->messages()->add('rut.required', 'Rut es requerido');
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $fechaHoy = Carbon::now()->format('Y-m-d');


            $consulta = "SELECT * FROM historial_instancia_reservas as h
                INNER JOIN reservas as r ON r.id = h.reserva_id
                INNER JOIN bloques as b ON b.id = h.bloque_id
                INNER JOIN sala_estudios as sal ON sal.reserva_id = r.id
                INNER JOIN users as u ON u.id=h.user_id
                INNER JOIN ubicaciones as ubi ON ubi.id=r.ubicacione_id
                WHERE
                h.estado_instancia_id=1 AND /* es 1 ya que la instancia debe estar reservada pero sin entregar*/
                u.rut=? AND /* variar el 3 por ? e ingresar lo capturado en frontend*/
                h.fecha_reserva=? /* variar la fecha por ? e ingresar lo capturado por el frontend*/";

            $resultados= DB::select($consulta, [strval($rut), $fechaHoy]);
            // dd($resultados);
            return view('salaestudio.entregar',compact('resultados','mostrarResultados'));
        } catch (\Throwable $th) {
            return back()->with('error', '¡Hubo un error al entregar la reserva!');
        }
    }

    public function post_entregar_resultados(Request $request){

        $resultadosSeleccionados = $request->input('resultado', []);
        foreach ($resultadosSeleccionados as $resultado) {
            $valores = explode(', ', $resultado);
            $fecha = $valores[0];
            $id_bloque = $valores[1];
            $reserva_id = $valores[2];
            $user_id = $valores[3];


            $instancia_reserva = DB::table('historial_instancia_reservas')->where('fecha_reserva', $fecha)->where('bloque_id', $id_bloque)->where('reserva_id', $reserva_id)->where('user_id', $user_id)->update(['estado_instancia_id' => 2 ]);

        }
        return redirect()->route('salaestudio_entregar') ->with("success","Sala estudio(s) entregada(s) correctamente");//->with('datos', $datos);
        
    } 


    /* ----------------------- RU09: Recepcionar--------------------------------*/
    public function get_recepcionar(){
        $resultados="";
        $mostrarResultados=false;
        return view('salaestudio.recepcionar',compact('resultados','mostrarResultados'));
    }

    public function post_recepcionar(Request $request){
        $fechaActual = date("Y-m-d", strtotime("now"));
        $rut = $request->input('rut');
        $mostrarResultados=false;

        $consulta = "SELECT * FROM historial_instancia_reservas as h
        INNER JOIN reservas as r ON r.id = h.reserva_id
        INNER JOIN bloques as b ON b.id = h.bloque_id
        INNER JOIN sala_estudios as se ON se.reserva_id = r.id
        INNER JOIN users as u ON u.id=h.user_id
        INNER JOIN ubicaciones as ubi ON ubi.id=r.ubicacione_id
        WHERE
        h.estado_instancia_id=2 AND /* es 1 ya que la instancia debe estar reservada pero sin entregar*/
        u.rut=? AND /* variar el 3 por ? e ingresar lo capturado en frontend*/
        h.fecha_reserva=? /* variar la fecha por ? e ingresar lo capturado por el frontend*/";

        $resultados=DB::select($consulta, [$rut, $fechaActual]);
        if (count($resultados)>0){
            $mostrarResultados=true;
        }
        return view('salaestudio.recepcionar',compact('resultados','mostrarResultados'));
    }
    
    
    public function post_recepcionar_resultados(Request $request){
        
        $resultadosSeleccionados = $request->input('resultados_seleccionados');
        
        foreach ($resultadosSeleccionados as $resultadoSeleccionado) {
            // Dividir el valor del checkbox usando el delimitador
            list($fecha_reserva, $reserva_id, $user_id, $bloque_id) = explode('|', $resultadoSeleccionado);

            $date = Carbon::now();
            $date = $date->format('Y-m-d');
            DB::table("historial_instancia_reservas")->insert([
                "fecha_reserva"=>$fecha_reserva,
                "user_id"=>$user_id,
                "bloque_id"=>$bloque_id,
                "reserva_id"=>$reserva_id,
                "fecha_estado"=>$date,
                "estado_instancia_id"=>3
            ]);
            // Realizar acciones con los valores originales de las columnas
        }
        return redirect()->route('salaestudio_recepcionar') ->with("success","Sala(s) recepcionada(s) correctamente");//->with('datos', $datos);
    }


    public function get_recepcionar_filtrado(){
        return view('salaestudio.recepcionar_filtrado');
    }

    public function post_recepcionar_filtrado(Request $request){
        return redirect()->route('salaestudio_recepcionar');//->with('datos', $datos);
    }

}
