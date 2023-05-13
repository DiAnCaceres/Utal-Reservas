@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
    <div class="botonera">
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Sala de Estudios</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salagimnasio_historial_estudiante') }}'">Salas Gimnasios</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('cancha_historial_estudiante') }}'">Canchas</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('implemento_historial_estudiante') }}'">Implemento</button>
    </div>

    <h1> Mi Historial de Reservas: Salas Estudio</h1>

    <div class="box_recepcionar_ligteblue">
        <div id="div_filtros">
            <form action="{{route('post_salaestudio_historial_estudiante')}}" method="POST">
                @csrf
                
                    <label for='Estado' style="margin-right: 200px;">Estado:</label>
                <select name="Estado" id="estado">
                @foreach($estados as $estado)
                    <option name="bloque" value="{{ $estado->id }}">{{ $estado->nombre_estado}}</option>
                @endforeach
                </select>
                
                <label for='fecha_inicio' style="margin-right: 200px;">Desde:</label>
                <input class="form-control" type="fecha-local" placeholder="Seleccionar fecha" name="fecha">
                @if ($errors->has('fecha'))
                    <span class="text-danger">{{ $errors->first('fecha') }}</span>
                @endif

                    <label for='Fecha_inicio' style="margin-right: 200px;">Hasta:</label>
                <input class="form-control" type="fecha-local" placeholder="Seleccionar fecha" name="fecha">
                @if ($errors->has('fecha'))
                    <span class="text-danger">{{ $errors->first('fecha') }}</span>
                @endif

                <label for='Ubicacion' style="margin-right: 200px;">Ubicacion:</label>
                <select name="Ubicacion" id="ubicacion">
                @foreach($ubicacionesEstudio as $ubicacion)
                    <option name="ubicacion" value="{{ $ubicacion->id }}">{{ $ubicacion->nombre_ubicacion}}</option>
                @endforeach
                </select>


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

                @if($mostrarResultados==true and $botonApretado==false)
                    <button type="submit">Aplicar filtro</button>
                @elseif($mostrarResultados==true and $botonApretado==true)
                    <button type="button"  onclick="window.location='{{ route('salaestudio_historial_estudiante') }}'">Volver al historial sin filtro</button>
                @endif

            </form>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    
                
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>flatpickr("input[type=fecha-local]",{})</script>
    </div>
    
    <div class="separacion"></div>

    <div class="box_historial_ligteblue">
        <div>
            @if($mostrarResultados==true && $botonApretado==false)
                    <table class = "table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Ubicacion</th>
                            <th>Hora de inicio</th>
                            <th>Hora de fin</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Fecha Estado</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tboby>
                    @foreach($resultados as $resultado)
                        <tr>
                        <!--  nombre 	nombre_ubicacion 	hora_inicio 	hora_fin 	fecha_reserva Ascendente 1 	estado 	fecha_estado 	-->
                        <p>
                            <th>{{$resultado->nombre}}</th>
                            <th>{{$resultado->nombre_ubicacion}}</th>
                            <th>{{$resultado->hora_inicio}}</th>
                            <th>{{$resultado->hora_fin}}</th>
                            <th>{{$resultado->fecha_reserva}}</th>
                            <th>{{$resultado->estado}}</th>
                            <th>{{$resultado->fecha_estado}}</th>
                        </p>
                    @endforeach
                @elseif($mostrarResultados==false && $botonApretado==false)
                    <p>No hay datos</p>
                @endif
                </div>
    </div>

@endsection