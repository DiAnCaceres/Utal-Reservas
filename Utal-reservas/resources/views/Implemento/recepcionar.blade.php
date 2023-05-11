@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

    <div class="botonera">
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salaestudio_recepcionar') }}'">Sala estudio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salagimnasio_recepcionar') }}'"> Salas gimnasio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('cancha_recepcionar') }}'">Cancha</button>
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Implemento</button>
    </div>

    <div class="box_recepcionar_ligteblue">
        <div id="div_resultados">

            <h1> Recepcionar Implemento</h1>

            <form action="{{route('post_implemento_recepcionar')}}" method="POST">
                @csrf
                <input type="text" placeholder="Rut: 12.345.678-9" name="rut">
                <button type="submit">Buscar reservas del usuario</button>
            </form>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>

    <br>

    <div class="box_recepcionar_ligteblue1">
        <div id="div_resultados">
            <form action="{{route('post_implemento_recepcionar_resultados')}}" method="POST">
                @csrf

                @if($mostrarResultados == true)
                    <!--BORRAR: Javier, actualmente mostrará resultados cuando tenga resultados en la query,
                    Sin embargo, al igual que con las anteriores, deberas mostrarlo en formato lista estos resultados
                     te recomiendo utilizar el dd($) para debuguear-->
                    <h> Resultados</h>
                @else
                    <p>No se encontraron resultados.</p>
                @endif

            </form>
        </div>
@endsection
