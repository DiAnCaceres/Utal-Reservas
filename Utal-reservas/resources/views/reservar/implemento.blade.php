@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

<div class="botonera">
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('reservar_sala_estudio') }}'">Salas de estudio</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('reservar_sala_gimnasio') }}'"> Salas del gimnasio</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('reservar_cancha') }}'">Canchas</button>
    <button type="button" class="btn btn-default col-xs-4 boton_activo">Implementos</button>
</div>

<div class="separacion">
</div>

<div class="contenedor">
    <div class="box_registro_ligteblue">

        <h1><b> Reservar implementos </b></h1>
        <label for='bloques' style="margin-right: 150px;">Bloques:</label>
        <select name=bloques" id="bloques">
            @foreach($bloquesDisponibles as $bloque)
                <option name="bloque" values="[{{ $bloque->hora_inicio }}{{ $bloque->hora_fin }}]">{{ $bloque->hora_inicio }} - {{ $bloque->hora_fin }}</option>
            @endforeach
        </select>

    </div>
</div>
@endsection
