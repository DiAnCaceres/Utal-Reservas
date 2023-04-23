@extends('layouts.plantilla')

@section('title', 'Registar Sala de Estudio')
@section('content')

<h1> aqui vamos a registrar las salas de estudio </h1>
    <button type="button" onclick="window.location='{{ route('login') }}'">Ir al Login</button>
    <button type="button" onclick="window.location='{{ route('registro_sala_estudio') }}'">Ir al Registro</button>
    <div>
    <button type="button" onclick="window.location='{{ route('registro_sala_estudio') }}'">Salas de estudio</button>
    <button type="button" onclick="window.location='{{ route('registro_sala_gimnasio') }}'">Salas del gimnasio</button>
    <button type="button" onclick="window.location='{{ route('registro_cancha') }}'">Canchas</button>
    <button type="button" onclick="window.location='{{ route('registro_implemento') }}'">Implementos</button>
    </div>


@endsection 