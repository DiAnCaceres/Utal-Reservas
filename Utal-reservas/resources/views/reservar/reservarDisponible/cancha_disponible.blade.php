@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

<div class="separacion">        <!-- Contenedor para un separador, esto con el fin de que quede en el centro el boloque celeste  -->
</div>

<div class="contenedorReserva">                     <!-- Contenedor general  -->
    <div class="box_registro_ligteblue">            <!-- Caja celeste que engloba a los datos  -->

        <h1><b> Resultados busqueda de cancha </b></h1>
        <form action="{{route('reservar_cancha.disponibilidad')}}" method="POST">
            @csrf
            <label for='textoSalas' style="margin-right: 200px;">Canchas disponibles:</label>
            <select name=seleccionCancha" id="salas">

                @foreach($canchasDisponible as $cancha)
                    <option name="cancha" value="{{ $cancha->nombre }}">{{ $cancha->nombre }}</option>
                @endforeach
            </select>

        </form>

    </div>
</div>
@endsection
