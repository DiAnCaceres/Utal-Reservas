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
        <form method="POST" action="post_salagimnasio_historial_moderador">
            @csrf
            <h1>Filtrar por:</h1>
            <div class="labels">
                <label for="">Estado</label>
                <select name="estado" class="opciones">
                    <option name="nombre_estado" value="0">Cualquier estado</option>
                    @foreach($estadosGimnasio as $estado)
                        <option name="nombre_estado" value="{{ $estado->id }}">{{ $estado->nombre_estado }}</option>
                    @endforeach
                </select>
            </div>

            <div class="labels">
                <label for="">Fecha inicio</label>
                <input class="form-control my-datepicker" type="fecha-local" placeholder="Seleccionar fecha inicio" name="fechaInicio">
                @if ($errors->has('fecha'))
                    <span class="text-danger">{{ $errors->first('fecha') }}</span>
                @endif
            </div>

            <div class="labels">
                <label for="">Fecha fin</label>
                <input class="form-control my-datepicker" type="fecha-local" placeholder="Seleccionar fecha fin" name="fechaFin">
                @if ($errors->has('fecha'))
                    <span class="text-danger">{{ $errors->first('fecha') }}</span>
                @endif
            </div>

            <div class="labels">
                <label for="">Ubicacion</label>
                <select name="ubicacion" class="opciones">
                    <option name="nombre_ubicacion" value="0">Cualquier ubicación</option>
                    @foreach($ubicacionesGimnasio as $ubicacion)
                        <option name="nombre_ubicacion" value="{{ $ubicacion->id }}">{{ $ubicacion->nombre_ubicacion }}</option>
                    @endforeach
                </select>
            </div>
            @if($mostrarResultados==true and $botonApretado==false)
                <div>
                    <button class="button" type="submit">Aplicar filtro</button>
                    
                </div>
            @elseif($mostrarResultados==true and $botonApretado==true)
                <button class="button" type="button"  onclick="window.location='{{ route('salagimnasio_historial_moderador') }}'">Volver al historial sin filtro</button>
            @endif
        </form>
        
    </div>


    <br>
    <div class="tabla">
        <h1> Historial Salas de Gimnasio</h1>
        <br>
        <div id="div_resultados">

            @if($mostrarResultados==true && $botonApretado==false)
                <table class="tabla_resultados2">
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

            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>flatpickr("input[type=fecha-local]",{})</script>
@endsection
