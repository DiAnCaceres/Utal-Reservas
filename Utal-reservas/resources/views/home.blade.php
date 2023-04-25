@extends('layouts.plantilla')

@section('title', 'Home')
@section('content')
    <h1>Bienvenido a la página principal</h1>
    <button type="button" onclick="window.location='{{ route('login') }}'">Ir al Login</button>
    <button type="button" onclick="window.location='{{ route('registro_sala_estudio') }}'">Ir al Registro</button>
    <button type="button" onclick="window.location='{{ route('registro_moderador') }}' ">Registrar moderador</button>
    <button type="button" onclick="window.location='{{ route('register') }}' ">Registrar usuario</button>

@endsection
