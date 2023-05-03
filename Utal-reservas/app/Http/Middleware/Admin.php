<?php

namespace App\Http\Middleware;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role == 1) {
            return $next($request);
        } else if (Auth::user()->role == 3) {
            return redirect()->route('usuario_menuestudiante');
        }else if (Auth::user()->role == 2) {
            return redirect()->route('usuario_menumoderador');
        }else if (!Auth::check()) {
            return redirect()->route('login');
        }

        return redirect()->route('login');
    }
}
