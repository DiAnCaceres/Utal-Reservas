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
    <button type="button" onclick="window.location='{{ route('salaestudio_reservar') }}'">Reservar sala estudio</button>
    <button type="button" onclick="window.location='{{ route('cancha_reservar') }}'">Reservar cancha</button>
    <button type="button" onclick="window.location='{{ route('salagimnasio_reservar') }}'">Reservar sala gimnasio</button>
    <button type="button" onclick="window.location='{{ route('implemento_reservar') }}'">Reservar implemento</button>

    <button type="button" onclick="window.location='{{ route('implemento_modificarcantidad_agregar') }}'">agregar implemento</button>
    <button type="button" onclick="window.location='{{ route('implemento_modificarcantidad_eliminar') }}'">eliminar implemento</button>

    <h1>Segundo vista reservar AUN NO CONECTAR AUN!!! ALONSO !</h1>

@endsection
