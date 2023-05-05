<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reserva\CanchaRequest;
use App\Models\Bloques;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                return redirect()->route('cancha_reservar')->with('error', "Tienes una reserva para el mismo día y el mismo bloque, especificamente reservaste: $nombre_reserva. NO PUEDES RESERVAR DOS SERVICIOS EN UN MISMO BLOQUE Y FECHA.");
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
            // $sala_estudio = DB::table("reservas")->find($id_sala_estudio); //Busco el registro
            $fecha_reserva=$request->input('fecha');

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
                    } else {
                        // El estudiante ya tiene dos reservas para la fecha indicada, no puedes hacer otra reserva
                    }
            } else {
                // El registro ya existe, no es necesario ingresarlo de nuevo
            }

            // DB::table("instancia_reservas")->insert([
            //     "fecha_reserva" => $fecha_reserva,
            //     "reserva_id" => $id_cancha,
            //     "user_id" => $id_usuario,
            //     "bloque_id" => $id_bloque,
            // ]);

            $estado_instancia_reserva = DB::table("estado_instancia_reservas")->where('nombre_estado', "reservado")->first();
            $id_estado_instancia = $estado_instancia_reserva->id;

            DB::table("historial_instancia_reservas")->insert([
                "fecha_reserva"=>$fecha_reserva,
                "user_id"=>$id_usuario,
                "bloque_id"=>$id_bloque,
                "reserva_id"=>$id_estado_instancia,
            ]);

            return redirect()->route('cancha_reservar');
        }catch (\Throwable $th){
            return back()->with('error', '¡Hubo un error al reservar!');
        }
    }

    /* ---------------------------------- SEMANA 4 ----------------------------------------*/

   
     /* ----------------------- RU019: Cancelar ---------------------------------*/
    public function get_cancelar(){
        return view('cancha.cancelar');
    }

    public function post_cancelar(Request $request){
        return redirect()->route('cancha_cancelar');//->with('datos', $datos);
    }

     /* ----------------------- RU20: Entregar---------------------------------*/

    public function get_entregar(){
        return view('cancha.entregar');
    }

    public function post_entregar(Request $request){
        return redirect()->route('cancha_entregar_filtrado');//->with('datos', $datos);
    }


    public function get_entregar_filtrado(){
        return view('cancha.entregar_filtrado');
    }

    public function post_entregar_filtrado(Request $request){
        return redirect()->route('cancha_entregar');//->with('datos', $datos);
    }

}
