@extends('layouts.plantilla')

@section('title', 'Registar Sala del Gimnasio')
@section('content')
<div class="row">
        <div class="botonera">
            <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('registro_sala_estudio') }}'">Salas de estudio</button>
            <button type="button" class="btn btn-default col-xs-4 boton_activo"> Salas del gimnasio</button>
            <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('registro_cancha') }}'">Canchas</button>
            <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('registro_implemento') }}'">Implementos</button>
        </div>
    </div>
   
     <h1> Aquí vamos a registrar las salas del gimnasio </h1>
   

    
@endsection 