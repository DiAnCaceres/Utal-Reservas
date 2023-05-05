@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

    <div class="botonera">
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salaestudio_cancelar') }}'">Salas de estudio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salagimnasio_cancelar') }}'"> Salas gimnasio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Canchas</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('implemento_cancelar') }}'">Implemento</button>

        <!--
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Implementos</button>
         -->
    </div>

    <h1> Cancelar cancha</h1>

     <form action="{{route('post_cancha_cancelar')}}" method="POST">
        @csrf
        <button type="submit">Cancelar</button>
    </form>

    <button class="button" onclick="window.location='{{route('usuario_menuestudiante')}}' ">Volver atrás</button>
@endsection