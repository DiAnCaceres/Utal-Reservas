@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')
@section('content')
    <h1>Bienvenido moderador, aquí puedes:</h1>
    <button type="button" onclick="window.location='{{ route('registro_estudiante') }}' ">Registrar usuario</button>
    
@endsection