@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
    <div class="botonera">
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salaestudio_historial_moderador') }}'">Salas de estudio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Salas Gimnasios</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('cancha_historial_moderador') }}'">Canchas</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('implemento_historial_moderador') }}'">Implemento</button>
    </div>

    <br>

    <div class="filtros">
        <h1>Filtrar por:</h1>
        <div>
            <label for="">Estado</label>
            <select name="estado" class="opciones">
                @foreach($estadosEstudio as $estado)
                    <option name="nombre_ubicacion" value="{{ $estado->id }}">{{ $estado->nombre_estado }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="">Fecha inicio</label>
            <input class="form-control my-datepicker" type="fecha-local" placeholder="Seleccionar fecha inicio" name="fechaInicio">
            @if ($errors->has('fecha'))
                <span class="text-danger">{{ $errors->first('fecha') }}</span>
            @endif
        </div>

        <div>
            <label for="">Fecha fin</label>
            <input class="form-control my-datepicker" type="fecha-local" placeholder="Seleccionar fecha fin" name="fechaFin">
            @if ($errors->has('fecha'))
                <span class="text-danger">{{ $errors->first('fecha') }}</span>
            @endif
        </div>

        <div>
            <label for="">Ubicacion</label>
            <select name="ubicacion" class="opciones">
                @foreach($ubicacionesEstudio as $ubicacion)
                    <option name="nombre_ubicacion" value="{{ $ubicacion->nombre_ubicacion }}">{{ $ubicacion->nombre_ubicacion }}</option>
                @endforeach
            </select>
        </div>
        @if($mostrarResultados==true and $botonApretado==false)
            <button class="button" type="submit">Aplicar filtro</button>
        @elseif($mostrarResultados==true and $botonApretado==true)
            <button class="button" type="button"  onclick="window.location='{{ route('salaestudio_historial_moderador') }}'">Volver al historial sin filtro</button>
        @endif
    </div>

    <br>
    <div class="box_entregar_ligteblue1"">
        <h1> Historial Salas de Estudio</h1>
        <br>
        <div id="div_resultados">

            @if($mostrarResultados==true && $botonApretado==false)
                <table class="tabla_resultados">
                    <thead>
                        <tr>
                            <th>Nombre estudiante</th>
                            <th>Nombre reserva</th>
                            <th>Nombre ubicación</th>
                            <th>Hora inicio</th>
                            <th>Hora fin</th>
                            <th>Fecha reserva</th>
                            <th>Estado</th>
                            <th>Fecha estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resultados as $resultado)
                
                        <tr>
                            <td>{{$resultado->nombre_estudiante}}</td>
                            <td>{{$resultado->nombre}}</td>
                            <td>{{$resultado->nombre_ubicacion}}</td>
                            <td>{{$resultado->hora_inicio}}</td>
                            <td>{{$resultado->hora_fin}}</td>
                            <td>{{$resultado->fecha_reserva}}</td>
                            <td>{{$resultado->estado}}</td>
                            <td>{{$resultado->fecha_estado}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @elseif($mostrarResultados==false && $botonApretado==false)
                <p>No hay datos</p>
            @endif

            <form action="{{route('post_salaestudio_historial_moderador')}}" method="POST">
                @csrf

                <!-- MOSTRAR LA TABLA DE LOS RESULTADOS -->
                @if($mostrarResultados == true and $botonApretado==true)
                    <!-- MOSTRAR LA TABLA DE LOS RESULTADOS - CON FILTRO -->
                    @foreach($resultados as $resultado)
                        <!--  nombre 	nombre_ubicacion 	hora_inicio 	hora_fin 	fecha_reserva Ascendente 1 	estado 	fecha_estado 	-->
                        <p>
                            {{$resultado}}
                        </p>
                    @endforeach
                @elseif($mostrarResultados == false and $botonApretado==true)
                    <p>Sin resultados para el filtro</p>
                @endif

                

            </form>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>flatpickr("input[type=fecha-local]",{})</script>
@endsection
