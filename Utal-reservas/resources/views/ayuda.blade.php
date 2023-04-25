@extends('layouts.plantilla')

@section('title', 'HelpDesk')
@section('content')
    <h1>Mesa de ayuda /Helpdesk
    +56 71 2201555 - mesadeayuda@utalca.cl</h1>
    <button type="button" onclick="window.location='{{ route('login') }}'">Volver al login</button>
    
@endsection   