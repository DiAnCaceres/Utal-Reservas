<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    // url por defecto al inicio del programa, retorna la vista home
    public function __invoke(){
        return view('usuario.login');
    }

    public function get_menuadministrador(){
        return view('usuario.menuadministrador');
    }

    public function get_menuestudiante(){
        return view('usuario.menuestudiante');
    }

    public function get_menumoderador(){
        return view('usuario.menumoderador');
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
                return view('usuario.home');
        }
    }

    public function post_login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            // Si las credenciales son correctas, redirigir al usuario a su página correspondiente
            return $this->get_redireccionar();
        }
    
        // Si las credenciales son incorrectas, adjuntar un mensaje de error a la sesión
        return redirect()->route('login')->with('error', 'El correo electrónico o la contraseña son incorrectos.');
    }
}
