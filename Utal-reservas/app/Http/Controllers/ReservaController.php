<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bloques;

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


    public function sala_estudio_disponibles(){

        return view('reservar.reservarDisponible.sala_estudio_disponible');
    }
    public function canchas_disponibles(){

        return view('reservar.reservarDisponible.cancha_disponible');
    }
    public function sala_gimnasio_disponibles(){

        return view('reservar.reservarDisponible.sala_gimnasio_disponible');
    }
    public function implemento_disponibles(){

        return view('reservar.reservarDisponible.implemento_disponible');
    }

}
