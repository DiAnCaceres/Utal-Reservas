@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')
@section('content')
    <section class="hero">
    <div class="hero-body">
        <p class="title">
        Bienvenido
        </p>
        <p class="subtitle">
        Moderador: {{ Auth::user()->name }}
        </p>
    </div>
    </section>
    
    <div class="columns">
        <div class="column">
            <div class="box">
                <div class="card-content">
                    <div class="content">
                        <p> User ID: {{ Auth::user()->id }} </p>
                    
                        <p> User Name: {{ Auth::user()->name }} </p>

                        <p> User Email: {{ Auth::user()->email }} </p>
                    </div>
                </div>
            </div>

        </div>
        <div class="column">

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
    
    <div class="buttons is-centered">
        <button class="button" type="button" onclick="window.location='{{ route('profile.edit') }}' ">Perfil</button>
        <button class="button" type="button" onclick="window.location='{{ route('register_moderador') }}' ">Registrar moderador</button>
        <button class="button" type="button" onclick="window.location='{{ route('register_estudiante') }}' ">Registrar estudiante</button>
        <button class="button" type="button" onclick="window.location='{{ route('register_admin') }}' ">Registrar admin</button>
    </div>
    
    
    
    
@endsection