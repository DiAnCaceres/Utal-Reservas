@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')
    <h1> Entregar sala estudio - filtrado</h1>

    <form action="{{route('post_salaestudio_entregar_filtrado')}}" method="POST">
        @csrf
        <button type="submit">Entregar sala</button>
    </form>

    <button class="button" onclick="window.location='{{route('salaestudio_entregar')}}' ">Volver atrás</button>
@endsection