<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function __invoke(){
        return view('home');
    }

    public function login(){
        return view('login.index');
    }

    public function registrar_sala_estudio(){
        return view('registro.registrar_sala_estudio');
    }
}
