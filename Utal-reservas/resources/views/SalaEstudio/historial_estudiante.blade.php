@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')
    <h1> Mi Historial de Reservas: Salas Estudio</h1>

    <div class="box_recepcionar_ligteblue">
        <div id="div_resultados">

            @if($mostrarResultados==true && $botonApretado==false)
                @foreach($resultados as $resultado)
                    <!--  nombre 	nombre_ubicacion 	hora_inicio 	hora_fin 	fecha_reserva Ascendente 1 	estado 	fecha_estado 	-->
                    <p>
                        {{$resultado->nombre}} -
                        {{$resultado->nombre_ubicacion}} -
                        {{$resultado->hora_inicio}} -
                        {{$resultado->hora_fin}} -
                        {{$resultado->fecha_reserva}} -
                        {{$resultado->estado}} -
                        {{$resultado->fecha_estado}}
                    </p>
                @endforeach
            @elseif($mostrarResultados==false && $botonApretado==false)
                <p>No hay datos</p>
            @endif

            <form action="{{route('post_salaestudio_historial_estudiante')}}" method="POST">
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
    </div>

    <br>

@endsection
