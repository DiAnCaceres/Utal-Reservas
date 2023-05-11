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
                'fecha' => 'required|date|after_or_equal:today'
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

            // Compruebo si se selecciona un bloque horario válido para el día de hoy
            $fecha_actual = date('Y-m-d');
            if($fecha_actual == $fecha_reserva){
                $hora_actual = Carbon::now()->format('H:i:s');
                $bloque = DB::table('bloques')->where('id', $id_bloque_comprobacion)->first();

                if($hora_actual>$bloque->hora_inicio){
                    return back()->withErrors(['bloque' => 'La hora seleccionada no es válida.']);
                }

            }

            $comprobacion = "
                SELECT * FROM instancia_reservas
                INNER JOIN reservas ON reservas.id = instancia_reservas.reserva_id
                WHERE user_id=? AND fecha_reserva=? AND bloque_id=?
            ";

            $registrosUsuario=DB::select($comprobacion,[$id_usuario,$fecha_reserva,$id_bloque_comprobacion]);
            $cantidadReservas=count($registrosUsuario);

            if($cantidadReservas>0){
                $nombre_reserva = $registrosUsuario[0]->nombre;
                return redirect()->route('salagimnasio_reservar')->with('error', "Existe una reserva para el mismo día y el mismo bloque.");
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
            $bloque_sgte = $request->input('bloque_sgte');
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
                        //SI MARCA QUE QUIERE EL BLOQUE SIGUIENTE DEBE REGISTRAR Y VERIFICAR QUE NO EXISTA OTRA RESERVA
                        if ($bloque_sgte and $id_bloque!=12){
                            $bloque_siguiente = $id_bloque + 1;

                            //PRIMERO VERIFICAMOS QUE NO EXISTA OTRA RESERVA EN EL BLOQUE SGTE
                            $existeRegistroSgte = DB::table("instancia_reservas")->whereDate('fecha_reserva', $fecha_reserva)
                                ->where('reserva_id', $id_gimnasio)
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
                                        "reserva_id" => $id_gimnasio,
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
                                        "reserva_id"=>$id_gimnasio,
                                        "fecha_estado"=>$date,
                                        "estado_instancia_id"=>$id_estado_instancia
                                    ]);
                                } else {
                                    return redirect()->route('salagimnasio_reservar')->with('error','Se reservó la primera, pero tienes dos reservas en la misma fecha, no puedes reservar más hasta otro día.');
                                }
                            } else {
                                return redirect()->route('salagimnasio_reservar')->with('error','Primera reserva hecha, pero ya existe otra reserva en el siguiente bloque.');
                            }
                        }
                    } else {
                        return redirect()->route('salagimnasio_reservar')->with('error','Ya tienes dos reservas en la misma fecha, no puedes reservar más hasta otro día.');
                    }
            } else {
                return redirect()->route('salagimnasio_reservar')->with('error','Ya realizaste esta misma reserva');
            }
            //SE REALIZÓ LA RESERVA CORRECTAMENTE
            return redirect()->route('salagimnasio_reservar')->with("success","Sala gimnasio reservada correctamente");
        }catch (\Throwable $th){
            return back()->with('error', '¡Hubo un error al reservar!');
        }
    }

    /* ---------------------------------- SEMANA 4 ----------------------------------------*/


     /* ----------------------- RU13: Cancelar ---------------------------------*/
     public function get_cancelar(){
        $mostrarResultados=false;
        $user_id=Auth::user()->id;
        $reservas="SELECT * FROM historial_instancia_reservas as h
        INNER JOIN reservas as r ON r.id = h.reserva_id
        INNER JOIN bloques as b ON b.id = h.bloque_id
        INNER JOIN sala_gimnasios as se ON se.reserva_id = r.id
        WHERE h.estado_instancia_id=1 AND
        h.user_id=? AND
        NOT EXISTS (
            SELECT 1 FROM historial_instancia_reservas as h2
            WHERE h2.estado_instancia_id NOT IN (2,3,4,5) AND
            h2.fecha_reserva = h.fecha_reserva AND
            h2.reserva_id = h.reserva_id AND
            h2.user_id = h.user_id AND
            h2.bloque_id = h.bloque_id
        )";
        $resultados=DB::select($reservas,[$user_id]);
        if ($resultados!=[]){
            $mostrarResultados=true;
        }

         // Ejecutar la consulta y pasar el parámetro del usuario
        return view('salagimnasio.cancelar', ['reservas' => $resultados], ['mostrarResultados' => $mostrarResultados]);
        /*return($reservas);*/
    }

    public function post_cancelar(Request $request){
    $resultadosSeleccionados = $request->input('a_cancelar');
    if($resultadosSeleccionados!=null){
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
    }}
        return redirect()->route('salagimnasio_cancelar');//->with('datos', $datos);

    }

     /* ----------------------- RU14: Entregar---------------------------------*/

    public function get_entregar(){
        $resultados="";
        $mostrarResultados=false;
        return view('salagimnasio.entregar',compact('resultados','mostrarResultados'));
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
                INNER JOIN sala_gimnasios as sal ON sal.reserva_id = r.id
                INNER JOIN users as u ON u.id=h.user_id
                INNER JOIN ubicaciones as ubi ON ubi.id=r.ubicacione_id
                WHERE
                h.estado_instancia_id=1 AND /* es 1 ya que la instancia debe estar reservada pero sin entregar*/
                u.rut=? AND /* variar el 3 por ? e ingresar lo capturado en frontend*/
                h.fecha_reserva=? /* variar la fecha por ? e ingresar lo capturado por el frontend*/";

            $resultados= DB::select($consulta, [strval($rut), $fechaHoy]);
            return view('salagimnasio.entregar',compact('resultados','mostrarResultados'));
        } catch (\Throwable $th) {
            return redirect()->route('salagimnasio_entregar')->with('error', '¡Hubo un error al entregar la reserva!');
        }
    }

    public function post_entregar_resultados(Request $request){

        $resultadosSeleccionados = $request->input('resultado', []);

        if(empty($resultadosSeleccionados)){
            return redirect()->route('salagimnasio_entregar')->with('error',"Debe seleccionar una reserva");
        }

        foreach ($resultadosSeleccionados as $resultado) {
            $valores = explode(',', $resultado);
            $fecha = $valores[0];
            $id_bloque = $valores[1];
            $reserva_id = $valores[2];
            $user_id = $valores[3];


            $instancia_reserva = DB::table('historial_instancia_reservas')->where('fecha_reserva', $fecha)->where('bloque_id', $id_bloque)->where('reserva_id', $reserva_id)->where('user_id', $user_id)->update(['estado_instancia_id' => 2 ]);

        }
        return redirect()->route('salagimnasio_entregar') ->with("success","Sala gimnasio(s) entregada(s) correctamente");//->with('datos', $datos);

    }


    public function get_entregar_filtrado(){
        return view('salagimnasio.entregar_filtrado');
    }

    public function post_entregar_filtrado(Request $request){
        return redirect()->route('salagimnasio_entregar');//->with('datos', $datos);
    }


    /* ----------------------- RU10: Recepcionar--------------------------------*/
    public function get_recepcionar(){
        $resultados="";
        $mostrarResultados=false;
        return view('salagimnasio.recepcionar',compact('resultados','mostrarResultados'));
    }

    public function post_recepcionar(Request $request){
        $fechaActual = date("Y-m-d", strtotime("now"));
        $rut = $request->input('rut');
        $mostrarResultados=false;

        $consulta = "SELECT * FROM historial_instancia_reservas as h
        INNER JOIN reservas as r ON r.id = h.reserva_id
        INNER JOIN bloques as b ON b.id = h.bloque_id
        INNER JOIN sala_gimnasios as sg ON sg.reserva_id = r.id
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
        return view('salagimnasio.recepcionar',compact('resultados','mostrarResultados'));
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
        return redirect()->route('salagimnasio_recepcionar') ->with("success","Sala(s) recepcionada(s) correctamente");//->with('datos', $datos);
    }

    public function get_recepcionar_filtrado(){
        return view('salagimnasio.recepcionar_filtrado');
    }

    public function post_recepcionar_filtrado(Request $request){
        return redirect()->route('salagimnasio_recepcionar_filtrado');//->with('datos', $datos);
    }

}
