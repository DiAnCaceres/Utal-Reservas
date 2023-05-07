@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

     <div class="botonera">
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salaestudio_entregar') }}'">Salas de estudio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salagimnasio_entregar') }}'"> Salas gimnasio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('cancha_entregar') }}'">Canchas</button>
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Implementos</button>

    </div>

    <h1> Entregar implemento</h1>


    <form action="{{route('post_implemento_entregar')}}" method="POST">
        @csrf
        <button type="submit">Buscar reservas del usuario</button>
    </form>

     <div id="div_resultados">
         <h1> Resultados de busqueda </h1>
         <form action="{{route('post_implemento_entregar_resultados')}}" method="POST">
             @csrf

             @if($mostrarResultados == true && $resultados != "")
                 <p> {{$resultados}}</p>
                 <button type="submit">Entregar</button>
             @else
                 <p>No se encontraron resultados.</p>
             @endif

         </form>
     </div>

    <button class="button" onclick="window.location='{{route('usuario_menumoderador')}}' ">Volver menu</button>
@endsection
