@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

<div class="separacion">         <!-- Contenedor para un separador, esto con el fin de que quede en el centro el boloque celeste  -->
</div>

<div class="contenedorReserva">                 <!-- Contenedor general  -->
    <div class="box_reserva_ligteblue">                <!-- Caja celeste que engloba a los datos  -->

        <h1><b> Resultados busqueda sala de gimansio </b></h1>

        <form action="{{route('post_salagimnasio_reservar_filtrado')}}" method="POST">
            @csrf
            <div>
            <div class="separacion">         <!-- Contenedor para un separador, esto con el fin de que quede en el centro el boloque celeste  -->
            </div>

            <input name="bloque" type="hidden" value="{{$id_bloque}}">
            <input name="fecha" type="hidden" value="{{$fecha_reserva}}">

            <label for='textoSalas' style="margin-right: 200px;">Salas disponibles:

            <select name="seleccionSala" id="salas">

                @foreach($salasGimnasioDisponible as $sala)
                    <option name="sala" value="{{ $sala->reserva_id }}"> Nombre:{{ $sala->nombre }} / Ubicacion: {{ $sala->nombre_ubicacion }} / Capacidad: {{ $sala->capacidad }}</option>
                @endforeach
            </select>

            <div class="separacion">         <!-- Contenedor para un separador, esto con el fin de que quede en el centro el boloque celeste  -->
            </div>
            <div class="container">
                <input type="checkbox" id="miCheckbox" name="bloque_sgte">
                <label for="bloque_sgte">¿Desea reservar el siguiente bloque?</label>
            </div> 

            <button type="submit" class="button-reservar">Reservar<i class="ri-arrow-right-line"></i></button>
            <button type="button" class="button-volver" onclick="window.location='{{ route('salagimnasio_reservar') }}'">Volver</button>

        </form>
    </div>
</div>
@endsection
