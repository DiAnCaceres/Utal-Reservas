@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

    <div class="columns">

        <div class="column">
            <p class="title">
                Bienvenido
            </p>
            <p class="subtitle">
                Bienvenido administrador: {{ Auth::user()->name }}
            </p>

        </div>

        <div class="buttons column">
            {{-- <button class="button" type="button" onclick="window.location='{{ route('profile.edit') }}' ">Perfil</button> --}}
            <button class="button" type="button" onclick="window.location='{{ route('registro_sala_estudio') }}'">Ir al Registro</button>
            <button class="button" type="button" onclick="window.location='{{ route('register_moderador') }}' ">
                Registrar moderador
            </button>
    
        </div>

        <div class="column">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
    
                <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>
       
    </div>
@endsection