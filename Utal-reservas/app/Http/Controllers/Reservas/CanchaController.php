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
            DB::table("reservas")->insert([
                "nombre" => $request->nombre,
                "ubicacion" => $request->ubicacion,
                "estado" => "Disponible"
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
