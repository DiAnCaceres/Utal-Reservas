@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')
    <h1> Entregar implemento - filtrado</h1>

    <form action="{{route('post_implemento_entregar_filtrado')}}" method="POST">
        @csrf
        <button type="submit">Entregar implemento</button>
    </form>

    <button class="button" onclick="window.location='{{route('implemento_entregar')}}' ">Volver atrás</button>
@endsection