@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')
    <h1> Mi Historial de Reservas: Salas Gimnasio</h1>

    <div class="box_recepcionar_ligteblue">
        <div id="div_resultados">

            <h1> Recepcionar Cancha</h1>

            <form action="{{route('post_salagimnasio_historial_estudiante')}}" method="POST">
                @csrf
                <button type="submit">Aplicar filtro</button>

                <!-- MOSTRAR LA TABLA DE LOS RESULTADOS -->
                @if($mostrarResultados == true)
                    <!-- MOSTRAR LA TABLA DE LOS RESULTADOS - CON FILTRO -->
                    @foreach($resultados as $resultado)
                        <p> {{$resultado}}</p>
                    @endforeach
                @else
                    <!-- MOSTRAR LA TABLA DE LOS RESULTADOS - SIN FILTRO -->
                    @foreach($resultados as $resultado)
                        <p> {{$resultado}}</p>
                    @endforeach
                @endif



            </form>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>
@endsection
