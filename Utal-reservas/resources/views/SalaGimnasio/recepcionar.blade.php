@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

    <div class="botonera">
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salaestudio_recepcionar') }}'">Salas de estudio</button>
        <button type="button" class="btn btn-default col-xs-4 boton_servicios" onclick="window.location='{{ route('salagimnasio_recepcionar') }}'"> Salas gimnasio</button>

        <!--
        <button type="button" class="btn btn-default col-xs-4 boton_activo">Implementos</button>
         -->
    </div>
    
    <br><br>
    <div class="box_recepcionar_ligteblue">     
        <h1><b> Recepcion de sala de gimnasio: </b></h1>
    
        <form action="{{route('post_salagimnasio_recepcionar')}}" method="POST">
            @csrf
            <input type="text" placeholder="Rut: 12.345.678-9" name="rut">
            <button type="submit">Buscar reservas del usuario</button>
        </form>
    </div>
    <br>
    <div class="box_recepcionar_ligteblue1">
        <div id="div_resultados">
            <h1><b> Resultados de busqueda: </b></h1>
            <form action="{{route('post_salagimnasio_entregar')}}" method="POST">
                @csrf

                @if($mostrarResultados == true && $resultados != "")
                    <!--  <p> {{$resultados}}</p>  -->

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Nombre</th>
                                <th>Horario</th>
                                <th>Capacidad/Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                <th> </th>
                                <th> </th>
                                <th> </th>
                                <th> </th>
                            </tr>
                            
                        </tbody>
                    </table>


                <button class="button-register" type="submit">Recepcionar</button>
                
                @else
                    <p>No se encontraron resultados.</p>
                @endif

            </form>
        </div>
        <button class="button-volver" onclick="window.location='{{route('usuario_menumoderador')}}' ">Volver menu</button>
        

    
@endsection