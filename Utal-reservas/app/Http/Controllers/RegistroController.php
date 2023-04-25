<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ubicacion;

class RegistroController extends Controller
{
    public function sala_estudio(){
        // ALL SIN FILTRO
        //$ubicacionesEstudio = Ubicacion::all(); 
        
        // con filtro where
        $ubicacionesEstudio = Ubicacion::where('categoria', 'educativo')->get();
        
        
        // debug
        //dd($ubicacioneEstudio);
        return view('registro.registrar_sala_estudio', compact('ubicacionesEstudio'));
    }

    public function sala_gimnasio(){
        return view('registro.registrar_sala_gimnasio');
    }

    public function cancha(){
        return view('registro.registrar_cancha');
    }

    public function implemento(){
        return view('registro.registrar_implemento');
    }

    public function estudiante(){
        return view('registro.registrar_estudiante');
    }

    public function moderador(){
        return view('registro.registrar_moderador');
    }
    public function admin(){
        return view('registro.registrar_admin');
    }
}