<?php

namespace App\Http\Controllers\Reservas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PruebasController extends Controller
{
    public function pruebas(Request $request){
        try {
            //OBTENER EL USUARIO
            // $usuario=Auth::user();
            // $usuario=$request->user()->id;
            // dd($usuario);

            // $ldate = date('Y-m-d');
            // dd($ldate);

            DB::table("instancia_reservas")->insert([
                "bloque_id" => 1,
                "user_id" => 3,
                "fecha_reserva" => "2023-04-29",
                "reserva_id" => 1,
            ]);
        } catch (\Throwable $th) {
            
        }
    }
    
}
