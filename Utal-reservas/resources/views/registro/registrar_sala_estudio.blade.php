@extends('layouts.plantilla')

@section('title', 'Registar Sala de Estudio')
@section('content')

<div class="botonera">
    <button type="button" class="btn btn-default col-xs-4 boton_activo">Salas de estudio</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('registro_sala_gimnasio') }}'"> Salas del gimnasio</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('registro_cancha') }}'">Canchas</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('registro_implemento') }}'">Implementos</button>
</div>

<div class="separacion">
</div>

<div class="contenedor">

    <div class="box_registro_ligteblue">
        <h1> Registrar las salas de estudio</h1>
        <form action="{{route("registro_sala_estudio.store")}}" method="POST">
            @csrf
            <input type="text" placeholder="Nombre de sala" name="nombre">

            @if ($errors->has('nombre'))
                <div class="invalid-feedback">
                    {{ $errors->first('nombre') }}
                </div>
            @endif

            <input type="text" placeholder="Capacidad" name="capacidad">
            
            <button class="btnEntrar">Registrar<i class="ri-arrow-right-line"></i></button>


            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

        </form>
    </div>
</div>

@endsection 