@extends('layouts.plantilla')

@section('title', 'Registar Cancha')

@section('estilos')
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css"> --}}
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

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
        <form action="{{route("registro_cancha.store")}}" method="POST">
        @csrf
            <input type="text" placeholder="Nombre" name="nombre">

            @if ($errors->has('nombre'))
                <div class="invalid-feedback">
                    {{ $errors->first('nombre') }}
                </div>
            @endif
           
            <button class="btnEntrar">Registar<i class="ri-arrow-right-line"></i></button>


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