<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

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
    public function dashboard(){
        switch(Auth::user()->role){
            case 2:
                return redirect(RouteServiceProvider::HOME_MODERADOR);
                break;
            case 3:
                return redirect(RouteServiceProvider::HOME_ESTUDIANTE);
                break;
            case 1:
                return redirect(RouteServiceProvider::HOME_ADMIN);
                break;
            default:
            return view('home');
        } 
    }
}
