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
                Bienvenido estudiante: {{ Auth::user()->name }}
            </p>

        </div>

        <div class="buttons column">
            {{-- <button class="button" type="button" onclick="window.location='{{ route('profile.edit') }}' ">Perfil</button> --}}
            <button type="button" class="button" onclick="window.location='{{ route('salaestudio_reservar') }}'">Reservar sala estudio</button>
            <button type="button" class="button" onclick="window.location='{{ route('cancha_reservar') }}'">Reservar cancha</button>
            <button type="button" class="button" onclick="window.location='{{ route('salagimnasio_reservar') }}'">Reservar sala gimnasio</button>
            <button type="button" class="button" onclick="window.location='{{ route('implemento_reservar') }}'">Reservar implemento</button>
            
        </div>

        <div class="column">
            <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button class="button is-danger" :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </button>
            </form>
        </div>

    </div>
    
@endsection