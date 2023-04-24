<?php

namespace App\Http\Controllers\Reservas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reserva\CanchaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CanchaController extends Controller
{
    //
    public function store(CanchaRequest $request){
        $sql=true;
        try {
            DB::table("ubicaciones")->insert([
                "nombre_ubicacion"=>$request->nombre_ubicacion,
                "categoria"=>$request->categoria
            ]);
            $id_ubicacion= DB::getPdo()->lastInsertId();
            
            DB::table("estado_reservas")->insert([
                "nombre_estado"=>$request->nombre_estado
            ]);
            $id_estado = DB::getPdo()->lastInsertId();

            DB::table("reservas")->insert([
                "nombre" => $request->nombre,
                "estado_reserva_id" => $id_estado,
                "ubicacione_id" => $id_ubicacion
            ]);
            $id_reserva = DB::getPdo()->lastInsertId();

            DB::table("canchas")->insert([
                "reserva_id" => $id_reserva
            ]);
        } catch (\Throwable $th) {
            $sql=0;
        }
        if($sql == true){
            return back()->with("correcto","Cancha registrada correctamente");
        }
        else{
            return back()->with("incorrecto","Error al registrar");
        }
    }
}
