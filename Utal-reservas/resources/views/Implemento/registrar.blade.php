@extends('layouts.plantilla')

@section('title', 'Registar Implemento')

@section('estilos')
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css"> --}}
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

<div class="botonera">
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salaestudio_registrar') }}'">Salas de estudio</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salagimnasio_registrar') }}'"> Salas del gimnasio</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('cancha_registrar') }}'">Canchas</button>
    <button type="button" class="btn btn-default col-xs-4 boton_activo">Implementos</button>
</div>

<div class="separacion">
</div>

<div class="contenedor">

    <div class="box_registro_ligteblue">
        <h1> Registrar Implemento</h1>
        <form action="{{route('registro_implemento.store')}}" method="POST">
        @csrf
            <input type="text" placeholder="Nombre de implemento" name="nombre">

            @if ($errors->has('nombre'))
                <div class="invalid-feedback">
                    {{ $errors->first('nombre') }}
                </div>
            @endif

            <input type="text" placeholder="Cantidad" name="cantidad">
            @if ($errors->has('cantidad'))
                <div class="invalid-feedback">
                    {{ $errors->first('cantidad') }}
                </div>
            @endif

            <label for="ubicacion" style="margin-right: 150px;">Ubicacion:</label>
            <select name="nombre_ubicacion" id="ubicacion">
                @foreach($ubicacionesDeportivas as $ubicacion)
                <option name="nombre_ubicacion" value="{{ $ubicacion->nombre_ubicacion }}">{{ $ubicacion->nombre_ubicacion }}</option>
                @endforeach
            </select>

            <button class="button-register">Registar<i class="ri-arrow-right-line"></i></button>


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
