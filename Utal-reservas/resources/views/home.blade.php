@extends('layouts.plantilla')

@section('title', 'Home')

@section('estilos')
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css"> --}}
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')
    <h1>Bienvenido a la página principal</h1>
    <button type="button" onclick="window.location='{{ route('login') }}'">Ir al Login</button>

@endsection
