@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

<div class="botonera_implementos">
    <button type="button" class="btn btn-default col-xs-4 boton_implementos" onclick="window.location='{{ route('agregar_implemento') }}'">Agregar</button>
    <button type="button" class="btn btn-default col-xs-4 boton_implementos_activo">Eliminar</button>
</div>

<div class="separacion">
</div>

<div class="box_cantidades_ligteblue">
<label for="implemento" style="margin-right: 150px;">Implemento:</label>
            <select name="nombre_implemento" id="implemento">



<input type="text" placeholder="Cantidad" name="cantidad">

<button class="button-register">Eliminar<i class="ri-arrow-right-line"></i></button>

</div>

@endsection