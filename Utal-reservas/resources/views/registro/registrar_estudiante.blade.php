@extends('layouts.plantilla')

@section('title', 'Registar estudiante')
@section('content')

{{-- <div class="botonera">
    <button type="button" class="btn btn-default col-xs-4 boton_activo">Moderadores</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('registro_moderador') }}'">Estudiantes</button>
</div> --}}

<div class="separacion">
</div>

<div class="contenedor">

    <div class="registro">
        <h1>Registrar estudiante</h1>
        <form action="{{route("registro_estudiante.store")}}" method="POST">
            @csrf
            <input type="text" placeholder="Nombre" name="nombre">
            <input type="text" placeholder="Mail" name="mail">
            <input type="text" placeholder="Rut" name="rut">
            <input type="text" placeholder="Matrícula" name="matricula">
            <input type="password" placeholder="Contraseña" name="contraseña">
            <input type="password" placeholder="Repetir Contraseña" name="">
            <button type="submit" class="btnEntrar">Registrar<i class="ri-arrow-right-line"></i></button>
        </form>
    </div>
</div>

@endsection 