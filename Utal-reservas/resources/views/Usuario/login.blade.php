@extends('layouts.plantilla')
@section('title', 'Login')

@section('estilos')
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css"> --}}
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

    <div class="contenedor">

        <div class="login">
            <h1>Bienvenido Utalino, por favor inicia sesión </h1>
            <form action="{{route('login')}}" method="POST"> <!--Metodo para validar rut y contraseña en bd-->
                @csrf
                <input id="email" placeholder="Email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            placeholder="Contraseña"
                            required autocomplete="current-password" />
                <button type= "submit" class="button-register">Entrar<i class="ri-arrow-right-line"></i></button>

            </form>
            <div class="separacion">
                <hr>
                <span>o</span>
                <hr>
            </div>

            <a class="btnAyuda" href="{{ route('ayuda') }}">¿Necesitas ayuda?</a>

        </div>

        <div class="imagen">
            <img src=" {{asset('img/login.png')}} " alt="" >
        </div>

    </div>

@endsection
