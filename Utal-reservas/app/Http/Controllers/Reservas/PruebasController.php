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
            $usuario=$request->user()->id;
            dd($usuario);
        } catch (\Throwable $th) {
            
        }
    }
    
}
