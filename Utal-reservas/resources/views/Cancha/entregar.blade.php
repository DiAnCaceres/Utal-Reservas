@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

    <div class="botonera">
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salaestudio_entregar') }}'">Salas de estudio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salagimnasio_entregar') }}'"> Salas gimnasio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Canchas</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('implemento_entregar') }}'">Implemento</button>

        <!--
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Implementos</button>
         -->
    </div>

    <br>

    <div class="box_entregar_ligteblue1">
        <div id="div_resultados">

            <h1> Entregar cancha</h1>

            <form action="{{route('post_cancha_entregar')}}" method="POST">
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

    <div class="box_entregar_ligteblue1">
        <div id="div_resultados">

            <h1> Resultados de busqueda </h1>
            <form action="{{route('post_cancha_entregar_resultados')}}" method="POST">
                @csrf

                @if($mostrarResultados == true)

                    <p>
                        <b>Nombre:</b> {{ $resultados[0]->name }} <br>
                        <b>Rut:</b> {{ $resultados[0]->rut }} <br>
                        <b>Correo:</b> {{ $resultados[0]->email }} <br>

                    </p>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Nombre</th>
                                <th>Hora inicio</th>
                                <th>Hora fin</th>
                                <th>Ubicacion </th>
                                <th>Entregar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resultados as $resultado)
                            <tr>
                                <th> {{ $resultado->fecha_reserva }} </th>
                                <th> {{ $resultado->nombre }} </th>
                                <th> {{ $resultado->hora_inicio }} </th>
                                <th> {{ $resultado->hora_fin }} </th>
                                <th> {{ $resultado->nombre_ubicacion }} </th>
                                <td>
                                    <input type="checkbox" name="resultados_seleccionados[]" value="{{ $resultado->fecha_reserva }}|{{ $resultado->reserva_id }}|{{ $resultado->user_id }}|{{ $resultado->bloque_id }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="submit">Entregar</button>
                    
                @else
                    <p>No se encontraron resultados.</p>
                @endif

            </form>
        </div>
    </div>
    <button class="button" onclick="window.location='{{route('usuario_menumoderador')}}' ">Volver menu</button>
    
@endsection