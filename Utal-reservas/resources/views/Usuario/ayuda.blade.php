@extends('layouts.plantilla')

@section('title', 'HelpDesk')

@section('estilos')
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css"> --}}
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')
    <h1>Mesa de ayuda /Helpdesk
    +56 71 2201555 - mesadeayuda@utalca.cl</h1>
    <button type="button" onclick="window.location='{{ route('login') }}'">Volver al login</button>
    
@endsection   