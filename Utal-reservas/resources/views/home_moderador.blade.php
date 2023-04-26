@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')
@section('content')
    <h1>Bienvenido Moderador: {{ Auth::user()->name }}</h1>
    <hr>
    <p> User ID: {{ Auth::user()->id }} </p>
    
    <p> User Name: {{ Auth::user()->name }} </p>

    <p> User Email: {{ Auth::user()->email }} </p>
    <hr>
    <button type="button" onclick="window.location='{{ route('profile.edit') }}' ">Perfil</button>
    <button type="button" onclick="window.location='{{ route('register_moderador') }}' ">Registrar moderador</button>
    <button type="button" onclick="window.location='{{ route('register_estudiante') }}' ">Registrar estudiante</button>
    <button type="button" onclick="window.location='{{ route('register_admin') }}' ">Registrar admin</button>
    
    <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
    </form>
@endsection