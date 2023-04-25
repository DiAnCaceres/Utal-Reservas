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

    public function home_estudiante(){
        return view('home_estudiante');
    }

    public function home_moderador(){
        return view('home_moderador');
    }
    
    public function home_admin(){
        return view('home_admin');
    }

    public function registrar_sala_estudio(){
        return view('registro.registrar_sala_estudio');
    }
}
