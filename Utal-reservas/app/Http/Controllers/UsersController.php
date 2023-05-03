<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    // url por defecto al inicio del programa, retorna la vista home
    public function __invoke(){
        return view('home');
    }

    public function get_menuadministrador(){
        return view('home_admin');
    }

    public function get_menuestudiante(){
        return view('home_estudiante');
    }

    public function get_menumoderador(){
        return view('home_moderador');
    }

    public function get_redireccionar(){
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
