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

        <form action="{{route('post_salaestudio_reservar_filtrado')}}" method="POST">
            @csrf
            <div>
            <div class="separacion">         <!-- Contenedor para un separador, esto con el fin de que quede en el centro el boloque celeste  -->
            </div>

            <label for='textoSalas' style="margin-right: 200px;">Salas disponibles:

            <select name=seleccionSala" id="salas">
                <!-- -->
                @foreach($salasEstudioDisponible as $sala)
                    <option name="sala" value="{{ $sala->id }}">{{ $sala->nombre }}</option>
                @endforeach
            </select>

            <div class="separacion">         <!-- Contenedor para un separador, esto con el fin de que quede en el centro el boloque celeste  -->
            </div>

            <button type="submit" class="button-reservar">Reservar<i class="ri-arrow-right-line"></i></button>
            <button type="button" class="button-volver" onclick="window.location='{{ route('salaestudio_reservar') }}'">Volver</button>

        </form>
    </div>
</div>
@endsection
