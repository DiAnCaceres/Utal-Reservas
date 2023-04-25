@extends('layouts.plantilla')

@section('title', 'Registrar admin')
@section('content')

{{-- <div class="botonera">
    <button type="button" class="btn btn-default col-xs-4 boton_activo">Moderadores</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('registro_moderador') }}'">Estudiantes</button>
</div> --}}

<div class="separacion">
</div>

<div class="contenedor">

    <div class="registro">
        <h1>Registrar admin</h1>
        <form method="POST">
            @csrf
            <input type="text" placeholder="Nombre" name="name">
            <input type="email" placeholder="Mail" name="email">
            <input type="text" placeholder="Rut" name="rut">
            <input type="text" placeholder="Matrícula" name="matricula">
            <input type="password" placeholder="Contraseña" name="password">
            <input type="password" placeholder="Confirmar Contraseña" name="password_confirmation">
            <button type="submit" class="btnEntrar">Registrar<i class="ri-arrow-right-line"></i></button>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <x-input-error :messages="$errors->get('rut')" class="mt-2" />
        </form>
        
    </div>
</div>

@endsection 