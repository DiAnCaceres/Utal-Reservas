<?php

namespace App\Http\Controllers\Reservas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reserva\ImplementoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Ubicacion;

class ImplementoController extends Controller
{
    //
    public function store(ImplementoRequest $request){
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
        
        return view('registro.registrar_implemento', compact('ubicacionesDeportivas'));
    }
}
