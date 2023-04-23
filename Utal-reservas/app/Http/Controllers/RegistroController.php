<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegistroController extends Controller
{
    public function sala_estudio(){
        return view('registro.registrar_sala_estudio');
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
}