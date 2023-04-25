@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')
@section('content')
    <h1>Bienvenido Moderador</h1>
    <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
    </form>
    <button type="button" onclick="window.location='{{ route('registro_sala_estudio') }}'">Ir al Registro</button>
    <button type="button" onclick="window.location='{{ route('register_estudiante') }}' ">Registrar estudiante</button>
@endsection