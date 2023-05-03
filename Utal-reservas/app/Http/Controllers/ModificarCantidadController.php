<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Http\Controllers\Reservas\ImplementoController;
use App\Models\Implemento;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\DB;

class ModificarCantidadController extends Controller
{
    public function agregar(){
        $implementosDisponibles = Implemento::join('reservas','implementos.reserva_id','=','reservas.id')->get(['reservas.nombre','implementos.cantidad']);
        //  dd($implementosDisponibles);
        return view('modificar_cantidad_implemento.agregar',compact('implementosDisponibles'));
    }

    public function eliminar(){
        $implementosDisponibles = Implemento::join('reservas','implementos.reserva_id','=','reservas.id')->where('implementos.cantidad','>',0)->get(['reservas.nombre','implementos.cantidad']);
        return view('modificar_cantidad_implemento.eliminar',compact('implementosDisponibles'));
    }




}
