@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

    <div class="botonera">
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salaestudio_cancelar') }}'">Salas de estudio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Salas Gimnasio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('cancha_cancelar') }}'">Canchas</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('implemento_cancelar') }}'">Implemento</button>

        <!--
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Implementos</button>
         -->
         </div>
    <div class="separacion"></div>
    <div class="box_reserva_ligteblue">

    <form action="{{route('post_salagimnasio_cancelar')}}" method="POST">
        
        @csrf
        @if($mostrarResultados == true)
    <table class = "table table-striped">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Nombre</th>
            <th>Hora</th>
            <th>Capacidad</th>
            <th></th>
</tr>
</thead>
<tboby>
    @foreach($reservas as $cancelar)
        <tr>
            <th>{{$cancelar->fecha_reserva}}</th>
            <th>{{$cancelar->nombre}}</th>
            <th>{{$cancelar->hora_inicio}}</th>
            <th>{{$cancelar->capacidad}}</th>
            <th><input type="checkbox" name="a_cancelar[]" value="{{ $cancelar->fecha_reserva }}|{{ $cancelar->bloque_id }}|{{ $cancelar->reserva_id }}|{{ $cancelar->user_id}}"></th>
</tr>
    @endforeach
</tbody>
</table>
        <button type="submit">Cancelar</button>
        @else
                    <p>No tienes reservas para cancelar.</p>
                @endif
                
        </form>


    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif      
    
    <button class="button" onclick="window.location='{{route('usuario_menuestudiante')}}' ">Volver atrás</button>
    
    </div>
@endsection