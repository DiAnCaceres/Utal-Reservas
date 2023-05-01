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
            
            <!--  ACA IRAN LAS SALAS DISPONIBLES JUNTO A LOS DEATALLES DE CADA SALA  -->
            <label for='bloques' style="margin-right: 200px;">{{$fecha_reserva}}</label>
            <button type="button" onclick="window.location='{{ route('reservar_sala_estudio') }}'">Realizar nueva busqueda</button>
            <button class="button-register">Reservar<i class="ri-arrow-right-line"></i></button>

        </form>      
    </div>
</div>
@endsection
