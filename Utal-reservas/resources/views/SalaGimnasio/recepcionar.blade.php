@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

    <div class="botonera">
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salaestudio_recepcionar') }}'">Salas de estudio</button>
    <button type="button" class="btn btn-default col-xs-4 boton_activo">Salas gimnasio</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('cancha_recepcionar') }}'">Cancha</button>
    <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('implemento_recepcionar') }}'">Implemento</button>


        <!--
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Implementos</button>
         -->
    </div>

    <br>

    <div class="box_recepcionar_ligteblue">
        <div id="div_resultados">

            <h1> Recepcionar sala de gimnasio</h1>

            <form action="{{route('post_salagimnasio_recepcionar')}}" method="POST">
                @csrf
                <input type="text" placeholder="Rut: 12.345.678-9" name="rut">
                <button type="submit">Buscar reservas del usuario</button>
            </form>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>

    <br>

    <div class="box_recepcionar_ligteblue1">
        <div id="div_resultados">
            <form action="{{route('post_salagimnasio_recepcionar_resultados')}}" method="POST">
                @csrf

                @if($mostrarResultados == true)
                    <h1 align="left"> Datos del estudiante: </h1>
                    <p>
                        <b>Nombre:</b> {{ $resultados[0]->name }} <br>
                        <b>Rut:</b> {{ $resultados[0]->rut }} <br>
                        <b>Correo:</b> {{ $resultados[0]->email }} <br>
                    </p>
                    <h1 align="left"> Resultados de busqueda: </h1>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha       </th>
                                <th>Nombre      </th>
                                <th>Hora inicio </th>
                                <th>Hora fin    </th>
                                <th>Ubicacion   </th>
                                <th>Recepcionar   </th>
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

                    <button type="submit" style="display:inline-block">Recepcionar</button>
                    <button onclick="window.location='{{route('usuario_menumoderador')}}'" style="display:inline-block">Volver menu</button>
                @else
                    <p>No se encontraron resultados.</p>
                @endif

            </form>
        </div>
    </div>
@endsection
