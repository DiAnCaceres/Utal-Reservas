@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')
    <div class="botonera">
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salaestudio_entregar') }}'">Salas de estudio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Salas Gimnasio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('cancha_entregar') }}'">Canchas</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('implemento_entregar') }}'">Implemento</button>

        <!--
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Implementos</button>
         -->
    </div>

    <br>

   
      <div class="box_entregar_ligteblue">
        <div id="div_resultados">
          <h1> Entregar sala de gimnasio</h1>
          <form action="{{route('post_salagimnasio_entregar')}}" method="POST">
            @csrf
            
            
                <input type="text" placeholder="Rut: 12.345.678-9" name="rut">
                <button class="button" type="submit">Buscar reservas del usuario</button>
                
            @if ($errors->has('rut'))
                <span class="text-danger">{{ $errors->first('rut') }}</span>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
          </form>

        </div>
      </div>

      <br>
      
      <div class="box_entregar_ligteblue1">

        <div id="div_resultados">
          <h1> Resultados de busqueda </h1>
          <form action="{{route('post_salagimnasio_entregar_resultados')}}" method="POST">
              @csrf
  
              @if($mostrarResultados == true && !empty($resultados))

              <h1 align="left"> Datos del estudiante: </h1>
                <p>
                  <b>Nombre:</b> {{ $resultados[0]->name }} <br>
                  <b>Rut:</b> {{ $resultados[0]->rut }} <br>
                  <b>Correo:</b> {{ $resultados[0]->email }} <br>
                  
                </p>
              <h1 align="left"> Resultados de busqueda: </h1>

              <table class="tabla_resultados">
                  <thead>
                    <tr>
                      <th>Fecha</th>
                      <th>Nombre sala</th>
                      <th>Horario inicio</th>
                      <th>Horario fin</th>
                      <th>Capacidad</th>
                      <th>Entregar</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($resultados as $resultado)
                      <tr>
                        <td>{{ $resultado->fecha_reserva }}</td>
                        <td>{{ $resultado->nombre }}</td>
                        <td>{{ $resultado->hora_inicio }}</td>
                        <td>{{ $resultado->hora_fin }}</td>
                        <td>{{ $resultado->capacidad }}</td>
                        <td> 
                          <input type="checkbox" name="resultado[]" value="{{$resultado->fecha_reserva}}, {{$resultado->bloque_id}},{{$resultado->reserva_id}}, {{$resultado->user_id}}">
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                
              <button type="submit">Entregar</button>
              <button class="button" onclick="window.location='{{route('usuario_menumoderador')}}' ">Volver menu</button>
              @else
                  <p>No se encontraron resultados.</p>
              @endif
  
          </form>
         
      </div>
    </div>

      </div>
    
@endsection
