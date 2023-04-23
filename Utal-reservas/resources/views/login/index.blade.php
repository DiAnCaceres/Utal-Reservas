@extends('layouts.plantilla')

@section('title', 'Login')

@section('content')

    <div class="contenedor">

        <div class="login">

            <a href="{{ route('ayuda') }}" class="btnRegistro">¿Necesitas ayuda?</a>

            <div class="separacion">
                <hr>
                <span>o</span>
                <hr>
            </div>

            <form action="">

                <input type="text" placeholder="Rut">
                <input type="password" placeholder="Contraseña">
                <button class="btnEntrar">Entrar<i class="ri-arrow-right-line"></i></button>

            </form>
        </div>

        <div class="imagen">
            <img src=" {{asset('img/login.png')}} " alt="" >
        </div>

    </div>

@endsection