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

<div class="separacion">
</div>

<div class="contenedorReserva">
    <div class="box_reserva_ligteblue">
        <h1><b> Reservar cancha </b></h1>
            <form action="{{route('reservar_cancha')}}" method="POST">
                @csrf

                <label for='bloques' style="margin-right: 140px;">Bloques:</label>
                <select name=bloques" id="bloques">
                 @foreach($bloquesDisponibles as $bloque)
                    <option name="bloque" values="[{{ $bloque->hora_inicio }}{{ $bloque->hora_fin }}]">{{ $bloque->hora_inicio }} - {{ $bloque->hora_fin }}</option>
                @endforeach
                </select>

                
                <input class="form-control" type="fecha-local" placeholder="Seleccionar fecha">
                

                <button class="button-register">Reservar<i class="ri-arrow-right-line"></i></button>

            </form>       


    </div>

        <div class="imagenReserva">
            <img src=" {{asset('img/ubicaciones.png')}} " alt="" >
        </div>

</div>
        
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>flatpickr("input[type=fecha-local]",{})</script>
@endsection
