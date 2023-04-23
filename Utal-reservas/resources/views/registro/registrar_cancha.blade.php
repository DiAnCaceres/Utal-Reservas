@extends('layouts.plantilla')

@section('title', 'Registar Cancha')
@section('content')

<div class="botonera">
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('registro_sala_estudio') }}'">Salas de estudio</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('registro_sala_gimnasio') }}'"> Salas del gimnasio</button>
    <button type="button" class="btn btn-default col-xs-4 boton_activo">Canchas</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('registro_implemento') }}'">Implementos</button>
</div>

<div class="separacion">
</div>

<div class="contenedor">

    <div class="box_registro_ligteblue">
        <h1> Registrar Cancha</h1>
        <form action="">

            <input type="text" placeholder="Nombre">
            <input type="text" placeholder="Ubicacion">
            <button class="btnEntrar">Entrar<i class="ri-arrow-right-line"></i></button>
        </form>
    </div>
</div>

@endsection 