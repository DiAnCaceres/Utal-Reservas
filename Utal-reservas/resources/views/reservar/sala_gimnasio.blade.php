@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')


<h1> Sección reservar sala del gimnasio </h1>
<label for='bloques' style="margin-right: 150px;">Bloques:</label>
<select name=bloques" id="bloques">
    @foreach($bloquesDisponibles as $bloque)
        <option name="bloque" values="[{{ $bloque->hora_inicio }}{{ $bloque->hora_fin }}]">{{ $bloque->hora_inicio }} - {{ $bloque->hora_fin }}</option>
    @endforeach
</select>

@endsection
