<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bloques;
use Illuminate\Support\Facades\Blade;

class ReservaController extends Controller
{
    public function sala_estudio(){
        $bloquesDisponibles = Bloques::all();
        return view('reservar.sala_estudio',compact('bloquesDisponibles'));
    }

    public function sala_gimnasio(){
        $bloquesDisponibles = Bloques::all();
        return view('reservar.sala_gimnasio',compact('bloquesDisponibles'));
    }
    public function cancha(){
        $bloquesDisponibles = Bloques::all();
        return view('reservar.cancha',compact('bloquesDisponibles'));
    }
    public function implemento(){
        $bloquesDisponibles = Bloques::all();
        return view('reservar.implemento',compact('bloquesDisponibles'));
    }
}
