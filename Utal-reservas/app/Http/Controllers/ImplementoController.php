<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reserva\ImplementoRequest;
use App\Models\Bloques;
use Illuminate\Http\Request;
use App\Models\Implemento;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ImplementoController extends Controller
{

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
        return view('implemento.registrar', compact('ubicacionesDeportivas'));
    }

    public function get_reservar(){
        $bloquesDisponibles = Bloques::all();
        return view('implemento.reservar',compact('bloquesDisponibles'));
    }

    public function get_reservar_filtrado(){
        try {
            $datos = session('datos');
            $implementosDisponible = $datos['implementosDisponible'];
            $id_bloque = $datos['id_bloque'];
            $fecha_reserva = $datos['fecha_reserva'];
            return view('Implemento.reservar_filtrado', compact('implementosDisponible', 'id_bloque', 'fecha_reserva'));

        } catch (\Throwable $th) {
            //throw $th;
            // return back()->with('error', 'Salió mal');
            return redirect()->route('implemento_reservar');
        }


    }

    public function get_modificarcantidad_agregar(){
        $implementosDisponibles = Implemento::join('reservas','implementos.reserva_id','=','reservas.id')->get(['reservas.nombre','implementos.cantidad','implementos.id']);

        //dd($implementosDisponibles);
        return view('implemento.agregar',compact('implementosDisponibles'));
    }

    public function get_modificarcantidad_eliminar(){
        $implementosDisponibles = Implemento::join('reservas','implementos.reserva_id','=','reservas.id')->where('implementos.cantidad','>',0)->get(['reservas.nombre','implementos.cantidad','implementos.id']);
        return view('implemento.eliminar',compact('implementosDisponibles'));
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
            $id_bloque=$request->input('bloques');

            //OBTENER FECHA DE LA RESERVA
            $fecha_reserva=$request->input('fecha');

            // Compruebo si se selecciona un bloque horario válido para el día de hoy
            $fecha_actual = date('Y-m-d');
            if($fecha_actual == $fecha_reserva){
                $hora_actual = Carbon::now()->format('H:i:s');
                $bloque = DB::table('bloques')->where('id', $id_bloque)->first();

                if($hora_actual>$bloque->hora_inicio){
                    return back()->withErrors(['bloque' => 'La hora seleccionada no es válida.']);
                }

            }

            $comprobacion = "SELECT * FROM instancia_reservas
                INNER JOIN reservas ON reservas.id = instancia_reservas.reserva_id
                WHERE user_id=? AND fecha_reserva=? AND bloque_id=?
            ";
            $registrosUsuario=DB::select($comprobacion,[$id_usuario,$fecha_reserva,$id_bloque]);
            $cantidadReservas=count($registrosUsuario);

            // Compruebo si la reserva corresponde a un gimnasio o cancha, de ser así, podrá reservar también un
            // implemento
            $consultaSalaEstudio = "SELECT * from sala_estudios
                INNER JOIN instancia_reservas ON sala_estudios.reserva_id = instancia_reservas.reserva_id
                WHERE fecha_reserva = ? AND user_id = ? AND bloque_id = ?";

            $esSalaEstudio = count(DB::select($consultaSalaEstudio, [$fecha_reserva, $id_usuario, $id_bloque]));

            if($cantidadReservas==0 or $esSalaEstudio == 0){
                $consulta = "SELECT * FROM implementos
                INNER JOIN reservas ON reservas.id = implementos.reserva_id
                INNER JOIN ubicaciones ON reservas.ubicacione_id = ubicaciones.id
                AND reserva_id NOT IN
                ( SELECT reservas.id FROM instancia_reservas
                INNER JOIN reservas ON instancia_reservas.reserva_id = reservas.id
                INNER JOIN implementos ON instancia_reservas.reserva_id = implementos.reserva_id
                WHERE instancia_reservas.fecha_reserva= ? AND instancia_reservas.bloque_id = ? AND implementos.cantidad <=
                ( SELECT COUNT(*) FROM instancia_reservas ir
                WHERE ir.fecha_reserva = instancia_reservas.fecha_reserva AND ir.reserva_id = instancia_reservas.reserva_id AND ir.bloque_id = instancia_reservas.bloque_id)
                GROUP BY reservas.id)";

                $implementosDisponible=DB::select($consulta, [$fecha_reserva, $id_bloque]);
                $datos = ["implementosDisponible" => $implementosDisponible, 'id_bloque' => $id_bloque, 'fecha_reserva' => $fecha_reserva];
                return redirect()->route('implemento_reservar_filtrado')->with('datos', $datos);


            }else{

                $nombre_reserva = $registrosUsuario[0]->nombre;
                return redirect()->route('implemento_reservar')->with('error', "Tienes una reserva para el mismo día y el mismo bloque, especificamente reservaste: $nombre_reserva. NO PUEDES RESERVAR DOS SERVICIOS EN UN MISMO BLOQUE Y FECHA.");

            }

        } catch (\Throwable $th) {
            return back()->with('error', 'Salió mal');
        }

    }

    public function post_reservar_filtrado(Request $request){
        try{
            $id_usuario= Auth::user()->id;
            $id_bloque=$request->input('bloque');
            $id_implemento = $request->input('seleccionImplemento');
            $fecha_reserva=$request->input('fecha');

            $existeRegistro = DB::table("instancia_reservas")->whereDate('fecha_reserva', $fecha_reserva)
                    ->where('reserva_id', $id_implemento)
                    ->where('user_id', $id_usuario)
                    ->where('bloque_id', $id_bloque)
                    ->doesntExist();

            if ($existeRegistro) {
                $numReservas = DB::table("instancia_reservas")->where('user_id', $id_usuario)
                        ->whereDate('fecha_reserva', $fecha_reserva)
                        ->count();

                    // Compruebo si la reserva corresponde a un gimnasio o cancha, de ser así, podrá reservar también un
                    // implemento
                    $consultaSalaEstudio = "SELECT * from sala_estudios
                    INNER JOIN instancia_reservas ON sala_estudios.reserva_id = instancia_reservas.reserva_id
                    WHERE fecha_reserva = ? AND user_id = ? AND bloque_id = ?";

                    $esSalaEstudio = count(DB::select($consultaSalaEstudio, [$fecha_reserva, $id_usuario, $id_bloque]));


                    // Verificamos si el número de reservas es menor o igual a 2
                    if ($numReservas < 2 or $esSalaEstudio==0) {
                        // El estudiante tiene menos de dos reservas para la fecha indicada, puedes proceder a hacer la reserva
                        DB::table("instancia_reservas")->insert([
                            "fecha_reserva" => $fecha_reserva,
                            "reserva_id" => $id_implemento,
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
                            "reserva_id"=>$id_implemento,
                            "fecha_estado"=>$date,
                            "estado_instancia_id"=>$id_estado_instancia
                        ]);
                    } else {
                        return redirect()->route('implemento_reservar')->with('error','Ya tienes dos reservas en la misma fecha, no puedes reservar más hasta otro día.');
                    }
            } else {
                return redirect()->route('implemento_reservar')->with('error','Ya realizaste esta misma reserva');
            }
            //SE REALIZÓ LA RESERVA CORRECTAMENTE
            return redirect()->route('implemento_reservar');
        }catch (\Throwable $th){
            return back()->with('error', '¡Hubo un error al reservar!');
        }
    }

    public function post_modificarcantidad_agregar(Request $request, Implemento $implemento)
    {
        $request->validate([
            "cantidad" => 'required',
        ]);
        $implemento=Implemento::find($request->id);
        $request['cantidad']=intval($request->cantidad)+intval($implemento->cantidad);

        //dd($request);
        $implemento->update($request->all());
        return  back()->with('success','implementos updated successfully');
    }
    public function post_modificarcantidad_eliminar(Request $request, Implemento $implemento)
    {
        $request->validate([
            "cantidad" => 'required',
        ]);
        $implemento=Implemento::find($request->id);
        //dd($request);
        $request['cantidad']=intval($implemento->cantidad)-intval($request->cantidad);
        if($request['cantidad']<0){
            return  back()->with('error','No se puede eliminar más implementos de los que hay');
        }

        //dd($request);
        $implemento->update($request->all());
        return  back()->with('success','implementos updated successfully');
    }


    /* ---------------------------------- SEMANA 4 ----------------------------------------*/


     /* ----------------------- RU019: Cancelar ---------------------------------*/
     public function get_cancelar(){
        $mostrarResultados = false;
        $user_id=Auth::user()->id;
        $reservas="";$reservas="SELECT *
        FROM (
            SELECT fecha_reserva, user_id, reserva_id, bloque_id, COUNT(*) AS total
            FROM historial_instancia_reservas AS h
            GROUP BY fecha_reserva, user_id, reserva_id, bloque_id
            HAVING total <= 1
        ) AS sub1
        INNER JOIN reservas as r ON r.id = sub1.reserva_id
        INNER JOIN bloques as b ON b.id = sub1.bloque_id
        INNER JOIN implementos as im ON im.reserva_id = r.id
        INNER JOIN users as u ON u.id=sub1.user_id
        INNER JOIN ubicaciones as ubi ON ubi.id=r.ubicacione_id
        WHERE u.id=?";
        $resultados=DB::select($reservas,[$user_id]);
        if ($resultados!=[]){
            $mostrarResultados=true;
        }

         // Ejecutar la consulta y pasar el parámetro del usuario
        return view('implemento.cancelar', ['reservas' => $resultados],['mostrarResultados' => $mostrarResultados]);
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
        return redirect()->route('implemento_cancelar');//->with('datos', $datos);

    }

     /* ----------------------- RU20: Entregar---------------------------------*/
    public function get_entregar(){
        $resultados="";
        $mostrarResultados=false;
        return view('implemento.entregar',compact('resultados','mostrarResultados'));
    }

    public function post_entregar(Request $request){

        $fechaActual = date("Y-m-d", strtotime("now"));
        $rut = $request->input('rut');
        $mostrarResultados=false;

        $consulta="SELECT *
            FROM (
                SELECT fecha_reserva, user_id, reserva_id, bloque_id, COUNT(*) AS total
                FROM historial_instancia_reservas AS h
                GROUP BY fecha_reserva, user_id, reserva_id, bloque_id
				 HAVING total >=1 AND total<2
            ) AS sub1
            INNER JOIN reservas as r ON r.id = sub1.reserva_id
            INNER JOIN bloques as b ON b.id = sub1.bloque_id
            INNER JOIN implementos as im ON im.reserva_id = r.id
            INNER JOIN users as u ON u.id=sub1.user_id
            INNER JOIN ubicaciones as ubi ON ubi.id=r.ubicacione_id
            WHERE u.rut=? AND sub1.fecha_reserva=?";

        $resultados=DB::select($consulta, [$rut, $fechaActual]);
        //dd($resultados);
        if (count($resultados)>0){
            $mostrarResultados=true;
        }

        return view('implemento.entregar',compact('resultados','mostrarResultados'));
    }

    public function post_entregar_resultados(Request $request){
        $resultadosSeleccionados = $request->input('resultados_seleccionados');
        if($resultadosSeleccionados!=null){
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
                    "estado_instancia_id"=>2
                ]);
                // Realizar acciones con los valores originales de las columnas
            }
            return redirect()->route('implemento_entregar') ->with("success","Implemento(s) entregdo(s) correctamente");//->with('datos', $datos);
        }
        return redirect()->route('implemento_entregar') ->with("success","Ninguna casilla fue seleccionada");//->with('datos', $datos);
    }

    public function get_entregar_filtrado(){
        return view('implemento.entregar_filtrado');
    }

    public function post_entregar_filtrado(Request $request){
        return redirect()->route('implemento_entregar');//->with('datos', $datos);
    }

    /* Semana 5*/

    /* RECEPCIONAR */
    public function get_recepcionar(){
        $resultados="";
        $mostrarResultados=false;
        return view('implemento.recepcionar',compact('resultados','mostrarResultados'));
    }

    public function post_recepcionar(Request $request){
        $rut = $request->input('rut');

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
        INNER JOIN implementos as im ON im.reserva_id = r.id
        INNER JOIN users as u ON u.id=sub1.user_id
        INNER JOIN ubicaciones as ubi ON ubi.id=r.ubicacione_id
        WHERE u.rut=?";

        $resultados=DB::select($consulta, [$rut]);
        // dd($resultados); // funciona, sólo queda mostrarlo en tabla al frontend y bueno, capturar los ticket para recepcionarlos ..
        if (count($resultados)>0){
            $mostrarResultados=true;
        } else{
            $mostrarResultados=false;
        }
        return view('implemento.recepcionar',compact('resultados','mostrarResultados'));
    }

    public function post_recepcionar_resultados(Request $request){
        // modificar acá en el interior
        return redirect()->route('implemento_recepcionar') ->with("success","Cancha(s) recepcionada(s) correctamente");//->with('datos', $datos);
    }

    /*---- Deshabilitar --- */
    public function get_deshabilitar(){
        $consulta = "SELECT r.nombre, ubi.nombre_ubicacion as ubicacion FROM reservas as r
        INNER JOIN implementos as im ON im.reserva_id= r.id
        INNER JOIN estado_reservas as er ON er.id = r.estado_reserva_id
        INNER JOIN ubicaciones as ubi ON ubi.id = r.ubicacione_id
        WHERE r.estado_reserva_id = 2";
        $resultados=DB::select($consulta);
        if (count($resultados)>0){
            $mostrarResultados=true;
        }else {
            $mostrarResultados=false;
        }
        return view('implemento.deshabilitar',compact('resultados','mostrarResultados'));
    }

    public function post_deshabilitar(Request $request){
        // capturar los tickeados y deshabilitarlos
        return redirect()->route('implemento_deshabilitar') ->with("success","Se ha deshabilitado correctamente tu seleccion");//->with('datos', $datos);
    }

    /*--- Historial estudiante ---*/
    public function get_historial_estudiante(){
        $consulta = "";
        $resultados=["resultados","sin","filtro"]; // demo, borrar hasta estar la consulta lista para que no arroje error x consulta vacia
        //$resultados=DB::select($consulta);
        if (count($resultados)>0){
            $mostrarResultados=true;
        }else {
            $mostrarResultados=false;
        }
        return view('Implemento.historial_estudiante',compact('resultados','mostrarResultados'));
    }

    public function post_historial_estudiante(Request $request){
        // la consulta aqui tendrá filtros, por tanto, debe modificarse según los que decida el programador
        $consulta = "";
        $resultados=["resultados","con","filtro"]; // demo, borrar hasta estar la consulta lista para que no arroje error x consulta vacia
        //$resultados=DB::select($consulta);
        if (count($resultados)>0){
            $mostrarResultados=true;
        }else {
            $mostrarResultados=false;
        }
        return view('Implemento.historial_estudiante',compact('resultados','mostrarResultados'));
    }
}
