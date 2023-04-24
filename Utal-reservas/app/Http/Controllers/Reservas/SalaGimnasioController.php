<?php

namespace App\Http\Controllers\Reservas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reserva\SalaGimnasioRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaGimnasioController extends Controller
{
    //
    public function store(SalaGimnasioRequest $request){
        $sql=true;
        try {
            DB::table("reservas")->insert([
                "nombre" => $request->nombre,
                "ubicacion" => $request->ubicacion,
                "estado" => "Disponible"
            ]);
            
            $id_reserva = DB::getPdo()->lastInsertId();
            
            DB::table("sala_gimnasios")->insert([
                "reserva_id" => $id_reserva,
                "capacidad" => $request->capacidad,
            ]);
        } catch (\Throwable $th) {
            $sql=0;
        }
        if($sql == true){
            return back()->with("correcto","Sala Gimnasio registrada correctamente");
        }
        else{
            return back()->with("incorrecto","Error al registrar");
        }
    }
}
