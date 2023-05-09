@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

    <div class="botonera">
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Salas Estudio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salagimnasio_entregar') }}'"> Salas gimnasio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('cancha_entregar') }}'">Canchas</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('implemento_entregar') }}'">Implementos</button>

        <!--
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Implementos</button>
         -->
    </div>

    

    <div class="entregar-reservas">
        <form action="{{route('post_salaestudio_entregar')}}" method="POST">
            @csrf
            <h1> Entregar sala estudio</h1>
            <div class="rut_usuario">
                <label for="">Rut</label>
                <input type="text" placeholder="Rut" name="rut">
                <button class="button" type="submit">Buscar reservas del usuario</button>
                
            </div>
            @if ($errors->has('rut'))
                <span class="text-danger">{{ $errors->first('rut') }}</span>
                @endif
        </form>
    
        <div id="div_resultados">
            <h1> Resultados de busqueda </h1>
            <form action="{{route('post_salaestudio_entregar_resultados')}}" method="POST">
                @csrf
    
                @if($mostrarResultados == true && $resultados != "")
                <table class="tabla_resultados">
                    <thead>
                      <tr>
                        <th>Fecha</th>
                        <th>Nombre sala</th>
                        <th>Horario inicio</th>
                        <th>Horario fin</th>
                        <th>Capacidad</th>
                        <th>Si</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($resultados as $resultado)
                        <tr>
                          <td>{{ $resultado->fecha }}</td>
                          <td>{{ $resultado->nombre }}</td>
                          <td>{{ $resultado->hora_inicio }}</td>
                          <td>{{ $resultado->hora_fin }}</td>
                          <td>{{ $resultado->capacidad }}</td>
                          <td> 
                            <input type="checkbox" name="resultado[]" value="{{$resultado->fecha}}, {{$resultado->nombre}}, {{$resultado->hora_inicio}}, {{$resultado->hora_fin}}, {{$resultado->capacidad}}">
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
            <button class="button" onclick="window.location='{{route('usuario_menumoderador')}}' ">Volver menu</button>
        </div>
    </div>
    

    
@endsection
