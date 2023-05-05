@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')

<div class="botonera">
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salaestudio_reservar') }}'">Salas de estudio</button>
    <button type="button" class="btn btn-default col-xs-4 boton_activo">Salas del gimnasio</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('cancha_reservar') }}'">Canchas</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('implemento_reservar') }}'">Implementos</button>
</div>

<div class="separacion">                            <!-- Contenedor para un separador, esto con el fin de que quede en el centro el boloque celeste  -->
</div>

<div class="contenedorReserva">                          <!-- Contenedor general  -->
    <div class="box_registro_ligteblue">                   <!-- Caja celeste que engloba a los datos  -->

        <h1><b> Reservar sala del gimnasio </b></h1>
        <form action="{{route('post_salagimnasio_reservar')}}" method="POST">
            @csrf
            <input class="form-control" type="fecha-local" placeholder="Seleccionar fecha" name="fecha">

            <label for='bloques' style="margin-right: 200px;">Bloques:</label>
            <select name="bloques" id="bloques">
            @foreach($bloquesDisponibles as $bloque)
                <option name="bloque" value="{{ $bloque }}">{{ $bloque->hora_inicio }} - {{ $bloque->hora_fin }}</option>
            @endforeach
            </select>

            <button type="submit">Buscar sala disponible</button>

            <!-- <button type="button" onclick="window.location='{{ route('salagimnasio_reservar_filtrado') }}'">Buscar canchas disponibles</button> -->
        </form>

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>flatpickr("input[type=fecha-local]",{})</script>

</div>
@endsection

