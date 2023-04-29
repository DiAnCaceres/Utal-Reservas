@extends('layouts.plantilla')

@section('title', 'Home')

@section('estilos')
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css"> --}}
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')
    <h1>Bienvenido a la página principal</h1> 
    <button type="button" onclick="window.location='{{ route('login') }}'">Ir al Login</button>
       
    ->Botones provisionales que despues se borran (hace mas facil el testeo)->
    <h1>NO PROTEGER ESTAS RUTAS HASTA QUE ESTEN COMPLETAS PORFAVOR ALONSO!</h1>
    <button type="button" onclick="window.location='{{ route('reservar_sala_estudio') }}'">Reservar sala estudio</button>
    <button type="button" onclick="window.location='{{ route('reservar_cancha') }}'">Reservar cancha</button>
    <button type="button" onclick="window.location='{{ route('reservar_sala_gimnasio') }}'">Reservar sala gimnasio</button>
    <button type="button" onclick="window.location='{{ route('reservar_implemento') }}'">Reservar implemento</button>

    <button type="button" onclick="window.location='{{ route('agregar_implemento') }}'">agregar implemento</button>
    <button type="button" onclick="window.location='{{ route('eliminar_implemento') }}'">eliminar implemento</button>


@endsection
