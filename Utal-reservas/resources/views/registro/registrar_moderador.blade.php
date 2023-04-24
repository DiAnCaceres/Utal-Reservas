@extends('layouts.plantilla')

@section('title', 'Registar Moderador')
@section('content')

{{-- <div class="botonera">
    <button type="button" class="btn btn-default col-xs-4 boton_activo">Moderadores</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('registro_moderador') }}'">Estudiantes</button>
</div> --}}

<div class="separacion">
</div>

<div class="contenedor">

    <div class="registro">
        <h1> Registrar moderador</h1>
        <form action="">
            <input type="text" placeholder="Nombre">
            <input type="text" placeholder="Mail">
            <input type="text" placeholder="Rut">
            <input type="password" placeholder="Contraseña">
            <input type="password" placeholder="Repetir Contraseña">

            <button class="btnEntrar">Registrar<i class="ri-arrow-right-line"></i></button>
        </form>
    </div>
</div>

@endsection 