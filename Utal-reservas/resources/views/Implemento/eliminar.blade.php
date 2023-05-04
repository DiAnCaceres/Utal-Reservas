@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')

@section('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
@endsection

@section('content')

<div class="botonera_implementos">
    <button type="button" class="btn btn-default col-xs-4 boton_implementos" onclick="window.location='{{ route('implemento_modificarcantidad_agregar') }}'">Agregar</button>
    <button type="button" class="btn btn-default col-xs-4 boton_implementos_activo">Eliminar</button>
</div>

<div class="separacion">
</div>
<div class="column is-half is-offset-one-quarter">
    <div class="card" style="background-color:#00cccc ">
    <div class="card-content">
        <div class="content">
            <form action="{{route('implemento_modificarcantidad_eliminar')}}" method="POST">
                @csrf
                    <label for="implementosDisponibles" style="margin-right: 150px;">Implemento:</label>
                    <select name="id" id="implementosDisponibles">
                        @foreach($implementosDisponibles as $implemento)
                            <option name="implemento" value="{{ $implemento->id}}"> Nombre: {{$implemento->nombre}} / Cantidad actual: {{ $implemento->cantidad }}</option>
                        @endforeach
                    </select>
                    
                    <input id="cantidad" type="text" placeholder="Cantidad" name="cantidad">
                    @if ($errors->has('cantidad'))
                        <div class="invalid-feedback">
                            {{ $errors->first('cantidad') }}
                        </div>
                    @endif

                    <button class="button-register">Eliminar<i class="ri-arrow-right-line"></i></button>


                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if($errors->any())
                        {!! implode('', $errors->all('<div>:message</div>')) !!}
                    @endif

                    </form>
                </div>
            </div>   
    </div>
</div>

@endsection