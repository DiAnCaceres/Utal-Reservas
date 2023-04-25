@extends('layouts.plantilla')
@section('title', 'Login')
@section('content')

    <div class="contenedor">

        <div class="login">

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

            <a href="{{ route('ayuda') }}" class="btnAyuda">¿Necesitas ayuda?</a>

        </div>

        <div class="imagen">
            <img src=" {{asset('img/login.png')}} " alt="" >
        </div>

    </div>

@endsection
