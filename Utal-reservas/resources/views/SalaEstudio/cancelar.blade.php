@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

    <div class="botonera">
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Salas Estudio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salagimnasio_cancelar') }}'"> Salas gimnasio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('cancha_cancelar') }}'">Canchas</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('implemento_cancelar') }}'">Implementos</button>

        <!--
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Implementos</button>
         -->
    </div>
    <div class="separacion"></div>
    <div class="box_reserva_ligteblue">
    <table class = "table table-striped">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Nombre</th>
            <th>Hora</th>
            <th>Capacidad</th>
</tr>
</thead>
<tboby>
    @foreach($reservas as $reserva)
        <tr>
            <th>{{$reserva->fecha}}</th>
            <th>{{$reserva->nombre}}</th>
            <th>{{$reserva->hora_inicio}}</th>
            <th>{{$reserva->capacidad}}</th>
            <th><input type="checkbox" name="opcion" value=$reserva></th>
</tr>
    @endforeach
</tbody>
</table>
    </div>
    <h1> Cancelar sala estudio</h1>

     <form action="{{route('post_salaestudio_cancelar')}}" method="POST">
        @csrf
        <button type="submit">Cancelar</button>
    </form>

    <button class="button" onclick="window.location='{{route('usuario_menuestudiante')}}' ">Volver atrás</button>
@endsection