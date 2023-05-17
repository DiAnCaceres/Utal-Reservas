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

    <div class="separacion"> </div>

    <div class="box_recepcionar_ligteblue">
        <div id="div_filtros">
            <form action="{{route('post_salaestudio_historial_estudiante')}}" method="POST">
                @csrf
            <h1>Filtrar por:</h1>
                
                    <label for='estado' style="margin-right: 200px;">Estado:</label>
                <select name="estado" value="0">
                    <option name="nombre_estado" value="0">Cualquier estado</option>
                @foreach($estadosEstudio as $estado)
                    <option name="nombre_estado" value="{{ $estado->id }}">{{ $estado->nombre_estado}}</option>
                @endforeach
                </select>
                
                <label for='fecha_inicio' style="margin-right: 200px;">Desde:</label>
                <input class="form-control" type="fecha-local" placeholder="Seleccionar fecha" name="fechaInicio">
                @if ($errors->has('fecha'))
                    <span class="text-danger">{{ $errors->first('fecha') }}</span>
                @endif

                    <label for='Fecha_inicio' style="margin-right: 200px;">Hasta:</label>
                <input class="form-control" type="fecha-local" placeholder="Seleccionar fecha" name="fechaFin">
                @if ($errors->has('fecha'))
                    <span class="text-danger">{{ $errors->first('fecha') }}</span>
                @endif

                <label for='ubicacion' style="margin-right: 200px;">Ubicacion:</label>
                <select name="ubicacion" id="ubicacion">
                    <option name="ubicacion" value="0">Cualquier ubicación</option>
                @foreach($ubicacionesEstudio as $ubicacion)
                    <option name="ubicacion" value="{{ $ubicacion->id }}">{{ $ubicacion->nombre_ubicacion}}</option>
                @endforeach
                </select>


                <div>
                    <button class="button" type="submit">Aplicar filtro</button>
                    
                </div>
</div>
            @if($mostrarResultados==true and $botonApretado==true)
                <button class="button" type="button"  onclick="window.location='{{ route('cancha_historial_estudiante') }}'">Volver al historial sin filtro</button>
            @endif
        </form>
        
    </div>

    @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif


    <br>
    <div class="box_historial_ligteblue">
        <h1> Historial canchas</h1>
        <br>
        <div>

            @if($mostrarResultados==true)
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
                    @foreach($resultadosPaginados as $resultado)
                        <tr>
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
    <div class="pagination">
        <ul class="pagination-list">
            <!-- Botón "Anterior" -->
            @if ($resultadosPaginados->onFirstPage())
                <li class="disabled"><span>&laquo;</span></li>
            @else
                <li><a href="{{ '?page=' . $resultadosPaginados->currentPage() - 1 }}" rel="prev" class="pagination-link">&laquo;</a></li>
            @endif
    
            <!-- Números de página -->
            @foreach ($resultadosPaginados->getUrlRange(1, $resultadosPaginados->lastPage()) as $page => $url)
                @if ($page == $resultadosPaginados->currentPage())
                    <li class="active"><span class="pagination-link current">{{ $page }}</span></li>
                @else
                    <li><a href="{{ '?page='.$page }}" class="pagination-link">{{ $page }}</a></li>
                @endif
            @endforeach
    
            <!-- Botón "Siguiente" -->
            @if ($resultadosPaginados->hasMorePages())
                @if (($resultadosPaginados->currentPage()) == 1)
                    <li><a href="{{ '?page=' . $resultadosPaginados->currentPage() + 1}}" rel="next" class="pagination-link">&raquo;</a></li>
                @else
                    <li><a href="{{str_replace(request()->path(), '' , '?page=' . $resultadosPaginados->currentPage() + 1)}}" rel="next" class="pagination-link">&raquo;</a></li>
                @endif
            @else
                <li class="disabled"><span>&raquo;</span></li>
            @endif
        </ul>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>flatpickr("input[type=fecha-local]",{})</script>

@endsection