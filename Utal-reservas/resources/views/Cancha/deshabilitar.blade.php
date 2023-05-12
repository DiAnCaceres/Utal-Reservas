@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

    <div class="botonera">
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salaestudio_deshabilitar') }}'"> Salas Estudio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salagimnasio_deshabilitar') }}'">Salas Gimnasio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Cancha</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('implemento_deshabilitar') }}'">Implemento</button>
    </div>

    <br>
    <h1 style="text-align:center; font-weight:bold;">Deshabilitar Sala Estudio</h1>
    <br>

    <div class="box_recepcionar_ligteblue1">
        <div id="div_resultados">
            <h2 style="text-align:center; font-weight:bold">Resultados de busqueda</h2>

            @if (session('success'))
                <div class="alert alert-success"  style="text-align:center; color:red; font-weight:bold;">
                    {{ session('success') }}
                </div>
            @endif



            <form action="{{route('post_cancha_deshabilitar')}}" method="POST">
                @csrf

                @if($mostrarResultados == true)
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Nombre      </th>
                            <th>Ubicación      </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($resultados as $resultado)
                            <tr>
                                <th> {{ $resultado->nombre }} </th>
                                <th> {{ $resultado->ubicacion }} </th>
                                <td>
                                    <input type="checkbox" name="resultados_seleccionados[]" value="{{ $resultado->id }}">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <button type="submit" style="display:inline-block">Deshabilitar seleccionados</button>
                @else
                    <p>No se encontraron resultados.</p>
                @endif

            </form>

            <div style="text-align:center;">
                <button onclick="window.location='{{route('usuario_menumoderador')}}'" style="display:inline-block;">Volver menu</button>
            </div>


        </div>

@endsection
