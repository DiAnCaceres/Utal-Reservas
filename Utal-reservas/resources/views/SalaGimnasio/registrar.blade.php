@extends('layouts.plantilla')

@section('title', 'Registar Sala del Gimnasio')

@section('estilos')
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css"> --}}
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

<div class="botonera">
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salaestudio_registrar') }}'">Salas de estudio</button>
    <button type="button" class="btn btn-default col-xs-4 boton_activo"> Salas del gimnasio</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('cancha_registrar') }}'">Canchas</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('implemento_registrar') }}'">Implementos</button>
</div>

<div class="separacion">
</div>

<div class="contenedor">

    <div class="box_registro_ligteblue">
        <h1> Registrar las salas de Gimnasio</h1>
        <form action="{{route('post_salagimnasio_registrar')}}" method="POST">
            @csrf
            <input type="text" placeholder="Nombre de sala" name="nombre">

            @if ($errors->has('nombre'))
                <div class="invalid-feedback">
                    {{ $errors->first('nombre') }}
                </div>
            @endif

            <input type="text" placeholder="Capacidad" name="capacidad">
            @if ($errors->has('capacidad'))
                <div class="invalid-feedback">
                    {{ $errors->first('capacidad') }}
                </div>
            @endif

            <label for="ubicacion" style="margin-right: 150px;">Ubicacion:</label>
            <select name="nombre_ubicacion" id="ubicacion">
                @foreach($ubicacionesDeportivas as $ubicacion)
                <option name="nombre_ubicacion" value="{{ $ubicacion->nombre_ubicacion }}">{{ $ubicacion->nombre_ubicacion }}</option>
                @endforeach
            </select>

            <button class="button-register">Registrar<i class="ri-arrow-right-line"></i></button>


            <button class="button" onclick="window.location='{{route('usuario_menuadministrador')}}' ">Volver atrás</button>

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
