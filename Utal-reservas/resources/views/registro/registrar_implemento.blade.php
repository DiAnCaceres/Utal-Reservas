@extends('layouts.plantilla')

@section('title', 'Registar Implemento')
@section('content')

<div class="botonera">
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('registro_sala_estudio') }}'">Salas de estudio</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('registro_sala_gimnasio') }}'"> Salas del gimnasio</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('registro_cancha') }}'">Canchas</button>
    <button type="button" class="btn btn-default col-xs-4 boton_activo">Implementos</button>
</div>

<div class="separacion">
</div>

<div class="contenedor">

    <div class="box_registro_ligteblue">
        <h1> Registrar Implemento</h1>
        <form action="{{route("registro_implemento.store")}}" method="POST">
            @csrf
            <input type="text" placeholder="Nombre" name="nombre">
            <input type="text" placeholder="Cantidad" name="cantidad">
            <input type="text" placeholder="Ubicacion" name="nombre_ubicacion">
            <button type="submit" class="btnEntrar">Registrar<i class="ri-arrow-right-line"></i></button>
        </form>
    </div>
</div>

@endsection 