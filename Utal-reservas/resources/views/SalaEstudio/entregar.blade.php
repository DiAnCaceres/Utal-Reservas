@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')
    <h1> Entregar sala estudio</h1>


    <form action="{{route('post_salaestudio_entregar')}}" method="POST">
        @csrf
    </form>


    <button class="button" onclick="window.location='{{route('salaestudio_entregar_filtrado')}}' ">Entregar sala filtrada</button>
    <button class="button" onclick="window.location='{{route('usuario_menumoderador')}}' ">Volver menu</button>
@endsection