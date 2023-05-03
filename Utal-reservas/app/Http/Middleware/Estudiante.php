<?php

namespace App\Http\Middleware;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Estudiante
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }else if (Auth::user()->role == 1) {
            return redirect()->route('usuario_menuadministrador');
        }else if (Auth::user()->role == 3) {
            return $next($request);
        }else if (Auth::user()->role == 2) {
            return redirect()->route('usuario_menumoderador');
        }

        return redirect()->route('login');
    }
}
