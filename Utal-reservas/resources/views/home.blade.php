@extends('layouts.plantilla')

@section('title', 'Home')
@section('content')
    <h1>bienvenido a la página principal</h1>
    <button type="button" onclick="window.location='{{ route('login') }}'">Ir al Login</button>
    <button type="button" onclick="window.location='{{ route('registro_btn') }}'">Ir a Registro</button>
@endsection
