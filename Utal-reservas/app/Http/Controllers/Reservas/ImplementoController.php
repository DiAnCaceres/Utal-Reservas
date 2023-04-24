<?php

namespace App\Http\Controllers\Reservas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reserva\ImplementoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImplementoController extends Controller
{
    //
    public function store(ImplementoRequest $request){
        $sql=true;
        try {
            // DB::table("ubicaciones")->insert([
            //     "nombre_ubicacion"=>$request->nombre_ubicacion,
            //     "categoria"=>$request->categoria
            // ]);
            // $id_ubicacion= DB::getPdo()->lastInsertId();

            // DB::table("estado_reservas")->insert([
            //     "nombre_estado"=>$request->nombre_estado
            // ]);
            // $id_estado = DB::getPdo()->lastInsertId();

            $ubi = DB::table("ubicaciones")
            ->where('nombre_ubicacion', $request->nombre_ubicacion)
            ->where('categoria', $request->categoria)
            ->first();
            if ($ubi) {
                $id_ubicacion = $ubi->id;
            } else {
                $ubi = DB::table("estado_reservas")->insertGetId([
                    "nombre_ubicacion"=>$request->nombre_ubicacion,
                    "categoria"=>$request->categoria
                ]);
                $id_ubicacion = $ubi;
            }
            
            $estado = DB::table("ubicaciones")
            ->where('nombre_estado', $request->nombre_estado)
            ->first();

            if ($estado) {
                $id_estado = $estado->id;
            } else {
                $estado = DB::table("estado_reservas")->insertGetId([
                    "nombre_estado"=>$request->nombre_estado
                ]);
                $id_estado = $estado;
            }

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
        } catch (\Throwable $th) {
            $sql=0;
        }
        if($sql == true){
            return back()->with("correcto","Implemento registrado correctamente");
        }
        else{
            return back()->with("incorrecto","Error al registrar");
        }
    }
}
