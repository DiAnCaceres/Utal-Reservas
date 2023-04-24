@extends('layouts.plantilla')

@section('title', 'Login')

@section('content')

    <div class="contenedor">

        <div class="login">
        
            <form action="{{route('login')}}" method="POST"> <!--Metodo para validar rut y contraseña en bd-->

            <input type="text" placeholder="Rut">
            <input type="password" placeholder="Contraseña">
            <button type= "submit" class="btnEntrar">Entrar<i class="ri-arrow-right-line"></i></button>
            
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