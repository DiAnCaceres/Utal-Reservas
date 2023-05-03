@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

<div class="separacion">         <!-- Contenedor para un separador, esto con el fin de que quede en el centro el boloque celeste  -->
</div>

<div class="contenedorReserva">             <!-- Contenedor general  -->
    <div class="box_registro_ligteblue">                <!-- Caja celeste que engloba a los datos  -->

        <h1><b> Resultados busqueda sala de estudio </b></h1>
        <form action="{{route('reservar_sala_estudio.disponibilidad')}}" method="POST">
            @csrf

            
            <input name="bloque" type="hidden" value="{{$id_bloque}}">
            <input name="fecha" type="hidden" value="{{$fecha_reserva}}">
            

            <label for='textoSalas' style="margin-right: 200px;">Salas disponibles:</label>
            <select name="seleccionSala" id="salas">
                <!-- -->
                @foreach($salasEstudioDisponible as $sala)
                    <option name="sala" value="{{ $sala->id }}">{{ $sala->nombre }}</option>
                @endforeach
            </select>
            <button type="submit">Registrar</button>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
