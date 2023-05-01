@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')

<div class="botonera">
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('reservar_sala_estudio') }}'">Salas de estudio</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('reservar_sala_gimnasio') }}'"> Salas del gimnasio</button>
    <button type="button" class="btn btn-default col-xs-4 boton_activo">Canchas</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('reservar_implemento') }}'">Implementos</button>
</div>

<div class="separacion">            <!-- Contenedor para un separador, esto con el fin de que quede en el centro el boloque celeste  -->
</div>

<div class="contenedorReserva">                  <!-- Contenedor general  -->
    <div class="box_reserva_ligteblue">             <!-- Caja celeste que engloba a los datos  -->

        <h1><b> Reservar cancha </b></h1>
        <form action="{{route('reservar_cancha')}}" method="POST">
            @csrf
            <input class="form-control" type="fecha-local" placeholder="Seleccionar fecha">

            <label for='bloques' style="margin-right: 200px;">Bloques:</label>
            <select name=bloques" id="bloques">
            @foreach($bloquesDisponibles as $bloque)
                <option name="bloque" values="[{{ $bloque->hora_inicio }}{{ $bloque->hora_fin }}]">{{ $bloque->hora_inicio }} - {{ $bloque->hora_fin }}</option>
            @endforeach
            </select>

            <button type="button" onclick="window.location='{{ route('reservar_canchas_disponibles') }}'">Buscar canchas disponibles</button>
        </form>      
    </div>
        
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>flatpickr("input[type=fecha-local]",{})</script>

</div>
@endsection
