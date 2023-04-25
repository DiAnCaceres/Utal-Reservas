@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')
@section('content')
    <h1>Bienvenido Admin, aquí puedes:</h1>
    <button type="button" onclick="window.location='{{ route('registro_sala_estudio') }}'">Registar servicios</button>
    <button type="button" onclick="window.location='{{ route('registro_moderador') }}' ">Registrar moderador</button>
    <button type="button" onclick="window.location='{{ route('registro_estudiante') }}' ">Registrar estudiante</button>
    
@endsection