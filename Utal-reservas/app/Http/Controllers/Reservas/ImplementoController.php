<?php

namespace App\Http\Controllers\Reservas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImplementoController extends Controller
{
    //
    public function store(Request $request){
        try {
            DB::table("reservas")->insert([
                "nombre" => $request->nombre,
                "ubicacion" => $request->ubicacion,
                "estado" => "Disponible"
            ]);
            
            $id_reserva = DB::getPdo()->lastInsertId();
            
            DB::table("implementos")->insert([
                "reserva_id" => $id_reserva,
                "cantidad" => $request->cantidad,
            ]);
        } catch (\Throwable $th) {
            //dar mensaje que no pudo crear
        }
    }
}
