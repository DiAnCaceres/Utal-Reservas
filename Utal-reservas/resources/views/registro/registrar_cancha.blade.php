@extends('layouts.plantilla')

@section('title', 'Registar Cancha')
@section('content')
<div class="row">
        <div class="botonera">
            <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('registro_sala_estudio') }}'">Salas de estudio</button>
            <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('registro_sala_gimnasio') }}'"> Salas del gimnasio</button>
            <button type="button" class="btn btn-default col-xs-4 boton_activo">Canchas</button>
            <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('registro_implemento') }}'">Implementos</button>
        </div>
    </div>
   
     <h1> Aquí vamos a registrar las canchas </h1>
   


@endsection 