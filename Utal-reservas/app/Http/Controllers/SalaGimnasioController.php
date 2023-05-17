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
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


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
                    WHERE reservas.estado_reserva_id=2
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
        $reservas="SELECT *
        FROM (
            SELECT fecha_reserva, user_id, reserva_id, bloque_id, COUNT(*) AS total
            FROM historial_instancia_reservas AS h
            GROUP BY fecha_reserva, user_id, reserva_id, bloque_id
            HAVING total <= 1
        ) AS sub1
        INNER JOIN reservas as r ON r.id = sub1.reserva_id
        INNER JOIN bloques as b ON b.id = sub1.bloque_id
        INNER JOIN sala_gimnasios as sg ON sg.reserva_id = r.id
        INNER JOIN users as u ON u.id=sub1.user_id
        INNER JOIN ubicaciones as ubi ON ubi.id=r.ubicacione_id
        WHERE u.id=?";
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


            $consulta="SELECT *
            FROM (
                SELECT fecha_reserva, user_id, reserva_id, bloque_id, COUNT(*) AS total
                FROM historial_instancia_reservas AS h
                GROUP BY fecha_reserva, user_id, reserva_id, bloque_id
				 HAVING total >=1 AND total<2
            ) AS sub1
            INNER JOIN reservas as r ON r.id = sub1.reserva_id
            INNER JOIN bloques as b ON b.id = sub1.bloque_id
            INNER JOIN sala_gimnasios as sg ON sg.reserva_id = r.id
            INNER JOIN users as u ON u.id=sub1.user_id
            INNER JOIN ubicaciones as ubi ON ubi.id=r.ubicacione_id
            WHERE u.rut=? AND sub1.fecha_reserva=?";

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

            $date = Carbon::now();
            $date = $date->format('Y-m-d');
            DB::table("historial_instancia_reservas")->insert([
                "fecha_reserva"=>$fecha,
                "user_id"=>$user_id,
                "bloque_id"=>$id_bloque,
                "reserva_id"=>$reserva_id,
                "fecha_estado"=>$date,
                "estado_instancia_id"=>2
            ]);
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

        $consulta = "SELECT *
        FROM (
            SELECT fecha_reserva, user_id, reserva_id, bloque_id, COUNT(*) AS total
            FROM historial_instancia_reservas AS h
            WHERE h.estado_instancia_id <> 5
            GROUP BY fecha_reserva, user_id, reserva_id, bloque_id
            HAVING total >= 2 AND total < 3
        ) AS sub1
        INNER JOIN reservas as r ON r.id = sub1.reserva_id
        INNER JOIN bloques as b ON b.id = sub1.bloque_id
        INNER JOIN sala_gimnasios as sg ON sg.reserva_id = r.id
        INNER JOIN users as u ON u.id=sub1.user_id
        INNER JOIN ubicaciones as ubi ON ubi.id=r.ubicacione_id
        WHERE u.rut=?";

        $resultados=DB::select($consulta, [$rut]);
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

    /*---- Deshabilitar --- */
    public function get_deshabilitar(){
        $consulta = "SELECT r.id, r.nombre, ubi.nombre_ubicacion as ubicacion FROM reservas as r
        INNER JOIN sala_gimnasios as sg ON sg.reserva_id= r.id
        INNER JOIN estado_reservas as er ON er.id = r.estado_reserva_id
        INNER JOIN ubicaciones as ubi ON ubi.id = r.ubicacione_id
        WHERE r.estado_reserva_id = 2";
        $resultados=DB::select($consulta);
        if (count($resultados)>0){
            $mostrarResultados=true;
        }else {
            $mostrarResultados=false;
        }
        return view('SalaGimnasio.deshabilitar',compact('resultados','mostrarResultados'));
    }

    public function post_deshabilitar(Request $request){
        $resultadosSeleccionados = $request->input('resultados_seleccionados');
        foreach ($resultadosSeleccionados as $idCapturado) {
            DB::table('reservas')->where('id', $idCapturado)->update(['estado_reserva_id' => 1]);
        }

        return redirect()->route('salagimnasio_deshabilitar') ->with("success","Se ha deshabilitado correctamente tu seleccion");
    }

    /*--- Historial estudiante ---*/
    public function get_historial_estudiante(){
        $user_id=Auth::user()->id;
        $botonApretado=false;
        $consulta = "SELECT r.nombre, ubi.nombre_ubicacion, blo.hora_inicio, blo.hora_fin, h.fecha_reserva, ei.nombre_estado as estado, h.fecha_estado FROM historial_instancia_reservas as h
        INNER JOIN sala_gimnasios as sg on sg.reserva_id = h.reserva_id
        INNER JOIN bloques as blo on blo.id = h.bloque_id
        INNER JOIN reservas as r on r.id = h.reserva_id
        INNER JOIN ubicaciones as ubi on ubi.id = r.ubicacione_id
        INNER JOIN users as u on u.id = h.user_id
        INNER JOIN estado_instancias as ei on ei.id = h.estado_instancia_id
        WHERE u.id=?
        ORDER BY h.fecha_reserva ASC, h.user_id ASC, h.bloque_id ASC, h.estado_instancia_id ASC";
        $ubicacionesDeportivas = Ubicacion::where('categoria', 'deportivo')->get();
        $estadosGimnasio= DB::table('estado_instancias')->get();
        $resultados=DB::select($consulta, [$user_id]);
        if (count($resultados)>0){
            $mostrarResultados=true;
        }else {
            $mostrarResultados=false;
        }

        // Convertir los resultados en una colección
        $coleccion = new Collection($resultados);

        // Crear la instancia de LengthAwarePaginator con la colección y la configuración de paginación
        $paginaActual = LengthAwarePaginator::resolveCurrentPage();
        $itemsPorPagina = 6; // Número de elementos por página
        $resultadosPaginados = new LengthAwarePaginator(
            $coleccion->forPage($paginaActual, $itemsPorPagina),
            $coleccion->count(),
            $itemsPorPagina,
            $paginaActual
        );
        return view('SalaGimnasio.historial_estudiante',compact('resultadosPaginados','resultados','mostrarResultados','botonApretado', 'ubicacionesDeportivas', 'estadosGimnasio'));
    }

    public function post_historial_estudiante(Request $request){
        $estadoSeleccionado = $request->input('estado');
        
        if(!$estadoSeleccionado == 0){
            
            $consultaEstados = "WHERE h.estado_instancia_id = $estadoSeleccionado -- Estado = 1
                                AND NOT EXISTS (
                                SELECT 1
                                FROM historial_instancia_reservas
                                WHERE reserva_id = h.reserva_id
                                AND bloque_id = h.bloque_id
                                AND estado_instancia_id > $estadoSeleccionado -- Estado en (2, 3, 4, 5)
            ) ";
        }else{
            $consultaEstados = "WHERE TRUE ";
        }
        
        // dd($consultaEstados);

        $fecha_inicio = $request->input('fechaInicio');
        if($fecha_inicio){
            $consultaFecha = "AND fecha_reserva >= '$fecha_inicio' ";
        }else{
            $consultaFecha = "AND TRUE";
        }
        
        $fecha_fin = $request->input('fechaFin');

        if($fecha_fin){
            $consultaFecha = $consultaFecha . " AND fecha_reserva <= '$fecha_fin' ";
        }

        $ubicacion = $request->input('ubicacion');
        if(!$ubicacion == 0){
            $consultaUbicacion = " AND ubi.id = $ubicacion";
        }else{
            $consultaUbicacion = "";
        }

        $consulta = "SELECT u.name as nombre_estudiante, r.nombre, ubi.nombre_ubicacion, blo.hora_inicio, blo.hora_fin, h.fecha_reserva, ei.nombre_estado as estado, h.fecha_estado
        FROM historial_instancia_reservas as h
        INNER JOIN sala_gimnasios as se on se.reserva_id = h.reserva_id
        INNER JOIN bloques as blo on blo.id = h.bloque_id
        INNER JOIN reservas as r on r.id = h.reserva_id
        INNER JOIN ubicaciones as ubi on ubi.id = r.ubicacione_id
        INNER JOIN users as u on u.id = h.user_id
        INNER JOIN estado_instancias as ei on ei.id = h.estado_instancia_id
        " . $consultaEstados . $consultaFecha . $consultaUbicacion . " 
        ORDER BY h.fecha_reserva ASC, h.user_id ASC, h.bloque_id ASC, h.estado_instancia_id ASC
        ";
        
        // dd($consulta);
        $resultados=DB::select($consulta);
        
        // dd($resultados);
        
        if (count($resultados)>0){
            $mostrarResultados=true;
        }else {
            $mostrarResultados=false;
        }
        $botonApretado=true;
        
        // Convertir los resultados en una colección
        $coleccion = new Collection($resultados);

        // Crear la instancia de LengthAwarePaginator con la colección y la configuración de paginación
        $paginaActual = LengthAwarePaginator::resolveCurrentPage();
        $itemsPorPagina = 6; // Número de elementos por página
        $resultadosPaginados = new LengthAwarePaginator(
            $coleccion->forPage($paginaActual, $itemsPorPagina),
            $coleccion->count(),
            $itemsPorPagina,
            $paginaActual
        );
        $ubicacionesDeportivas = Ubicacion::where('categoria', 'deportivo')->get();
        $estadosGimnasio = DB::table('estado_instancias')->get();
        return view('SalaGimnasio.historial_estudiante',compact('resultadosPaginados','resultados','mostrarResultados','botonApretado','estadosGimnasio', 'ubicacionesDeportivas'));
    }

    /*--- Historial moderador ---*/
    public function get_historial_moderador(){
        $botonApretado=false;
        $consulta = "SELECT u.name as nombre_estudiante,r.nombre, ubi.nombre_ubicacion, blo.hora_inicio, blo.hora_fin, h.fecha_reserva, ei.nombre_estado as estado, h.fecha_estado FROM historial_instancia_reservas as h
        INNER JOIN sala_gimnasios as se on se.reserva_id = h.reserva_id
        INNER JOIN bloques as blo on blo.id = h.bloque_id
        INNER JOIN reservas as r on r.id = h.reserva_id
        INNER JOIN ubicaciones as ubi on ubi.id = r.ubicacione_id
        INNER JOIN users as u on u.id = h.user_id
        INNER JOIN estado_instancias as ei on ei.id = h.estado_instancia_id
        ORDER BY h.fecha_reserva ASC, h.user_id ASC, h.bloque_id ASC, h.estado_instancia_id ASC";

        $resultados=DB::select($consulta);
        if (count($resultados)>0){
            $mostrarResultados=true;
        }else {
            $mostrarResultados=false;
        }

        // Convertir los resultados en una colección
        $coleccion = new Collection($resultados);

        // Crear la instancia de LengthAwarePaginator con la colección y la configuración de paginación
        $paginaActual = LengthAwarePaginator::resolveCurrentPage();
        $itemsPorPagina = 6; // Número de elementos por página
        $resultadosPaginados = new LengthAwarePaginator(
            $coleccion->forPage($paginaActual, $itemsPorPagina),
            $coleccion->count(),
            $itemsPorPagina,
            $paginaActual
        );

        $ubicacionesGimnasio = Ubicacion::where('categoria', 'deportivo')->whereNotIn('nombre_ubicacion',['aire libre'])->get();
        $estadosGimnasio = DB::table('estado_instancias')->get();
        return view('SalaGimnasio.historial_moderador',compact('resultadosPaginados','mostrarResultados','botonApretado', 'ubicacionesGimnasio', 'estadosGimnasio'));
    }

    public function post_historial_moderador(Request $request){
        // la consulta aqui tendrá filtros, por tanto, debe modificarse según los que decida el programador
        $estadoSeleccionado = $request->input('estado');
        
        if(!$estadoSeleccionado == 0){
            
            $consultaEstados = "WHERE h.estado_instancia_id = $estadoSeleccionado -- Estado = 1
                                AND NOT EXISTS (
                                SELECT 1
                                FROM historial_instancia_reservas
                                WHERE reserva_id = h.reserva_id
                                AND bloque_id = h.bloque_id
                                AND estado_instancia_id > $estadoSeleccionado -- Estado en (2, 3, 4, 5)
            ) ";
        }else{
            $consultaEstados = "WHERE TRUE ";
        }
        
        // dd($consultaEstados);

        $fecha_inicio = $request->input('fechaInicio');
        if($fecha_inicio){
            $consultaFecha = "AND fecha_reserva >= '$fecha_inicio' ";
        }else{
            $consultaFecha = "AND TRUE";
        }
        
        $fecha_fin = $request->input('fechaFin');

        if($fecha_fin){
            $consultaFecha = $consultaFecha . " AND fecha_reserva <= '$fecha_fin' ";
        }

        $ubicacion = $request->input('ubicacion');
        if(!$ubicacion == 0){
            $consultaUbicacion = " AND ubi.id = $ubicacion";
        }else{
            $consultaUbicacion = "";
        }

        $consulta = "SELECT u.name as nombre_estudiante, r.nombre, ubi.nombre_ubicacion, blo.hora_inicio, blo.hora_fin, h.fecha_reserva, ei.nombre_estado as estado, h.fecha_estado
        FROM historial_instancia_reservas as h
        INNER JOIN sala_gimnasios as se on se.reserva_id = h.reserva_id
        INNER JOIN bloques as blo on blo.id = h.bloque_id
        INNER JOIN reservas as r on r.id = h.reserva_id
        INNER JOIN ubicaciones as ubi on ubi.id = r.ubicacione_id
        INNER JOIN users as u on u.id = h.user_id
        INNER JOIN estado_instancias as ei on ei.id = h.estado_instancia_id
        " . $consultaEstados . $consultaFecha . $consultaUbicacion . " 
        ORDER BY h.fecha_reserva ASC, h.user_id ASC, h.bloque_id ASC, h.estado_instancia_id ASC
        ";
        
        // dd($consulta);
        $resultados=DB::select($consulta);
        
        // dd($resultados);
        
        if (count($resultados)>0){
            $mostrarResultados=true;
            $botonApretado=false;
        }else {
            $mostrarResultados=false;
            $botonApretado=false;
        }

        // Convertir los resultados en una colección
        $coleccion = new Collection($resultados);

        // Crear la instancia de LengthAwarePaginator con la colección y la configuración de paginación
        $paginaActual = LengthAwarePaginator::resolveCurrentPage();
        $itemsPorPagina = 6; // Número de elementos por página
        $resultadosPaginados = new LengthAwarePaginator(
            $coleccion->forPage($paginaActual, $itemsPorPagina),
            $coleccion->count(),
            $itemsPorPagina,
            $paginaActual
        );

        $ubicacionesGimnasio = Ubicacion::where('categoria', 'deportivo')->whereNotIn('nombre_ubicacion',['aire libre'])->get();
        $estadosGimnasio = DB::table('estado_instancias')->get();
        return view('SalaGimnasio.historial_moderador',compact('resultadosPaginados','mostrarResultados','botonApretado', 'ubicacionesGimnasio', 'estadosGimnasio'));
    }
}
