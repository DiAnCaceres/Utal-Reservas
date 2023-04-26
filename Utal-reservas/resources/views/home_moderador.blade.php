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
                Moderador: {{ Auth::user()->name }}
            </p>

        </div>
        {{-- <div class="column">
            <div class="card">
                <div class="card-content">
                    <div class="content">
                        <p> User ID: {{ Auth::user()->id }} </p>
                    
                        <p> User Name: {{ Auth::user()->name }} </p>

                        <p> User Email: {{ Auth::user()->email }} </p>
                        
                    </div>
                </div>
            </div>

        </div> --}}
        
        <div class="buttons column">
            {{-- <button class="button" type="button" onclick="window.location='{{ route('profile.edit') }}' ">Perfil</button> --}}
            <button class="button" type="button" onclick="window.location='{{ route('registro_sala_estudio') }}'">Ir al Registro</button>
            <button class="button" type="button" onclick="window.location='{{ route('register_estudiante') }}' ">Registrar estudiante</button>
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