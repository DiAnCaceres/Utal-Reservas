<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class ModificarCantidadController extends Controller
{
    public function agregar(){
        return view('modificar_cantidad_implemento.agregar');
    }
    
    public function eliminar(){
        return view('modificar_cantidad_implemento.eliminar');
    }




}