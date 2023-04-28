<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ubicacion;

class ReservaController extends Controller
{
    public function sala_estudio(){
        return view('reservar.sala_estudio');
    }
    public function sala_gimnasio(){
        return view('reservar.sala_gimnasio');
    }
    public function cancha(){
        return view('reservar.cancha');
    }
    public function implemento(){
        return view('reservar.implemento');
    }
}