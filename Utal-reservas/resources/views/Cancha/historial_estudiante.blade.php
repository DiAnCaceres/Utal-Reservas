@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
    <div class="botonera">
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salaestudio_historial_estudiante') }}'">Salas de estudio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salagimnasio_historial_estudiante') }}'"> Salas Gimnasio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Canchas</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('implemento_historial_estudiante') }}'">Implemento</button>
    </div>

    <div class="separacion"> </div>

    <div class="box_recepcionar_ligteblue">
        <form method="POST" action="post_cancha_historial_estudiante">
            @csrf
            <h1>Filtrar por:</h1>
            <label for='estado' style="margin-right: 200px;">Estado:</label>
                <select name="estado" value="0">
                    <option name="nombre_estado" value="0">Cualquier estado</option>
                @foreach($estadosCanchas as $estado)
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
                    <option name="nombre_ubicacion" value="0">Cualquier ubicación</option>
                @foreach($ubicacionesCanchas as $ubicacion)
                    <option name="nombre_ubicacion" value="{{ $ubicacion->id }}">{{ $ubicacion->nombre_ubicacion}}</option>
                @endforeach
                </select>

                <div>
                    <button class="button" type="submit">Aplicar filtro</button>
                    
                </div>
            @if($botonApretado==true)
                <button class="button" type="button"  onclick="window.location='{{ route('cancha_historial_estudiante') }}'">Volver al historial sin filtro</button>
            @endif
</div>
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
                <table class="table table-striped">
                    <thead>
                        <tr>
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
                        @foreach($resultadosPaginados as $resultado)
                
                        <tr>
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
            @elseif($mostrarResultados==false)
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
