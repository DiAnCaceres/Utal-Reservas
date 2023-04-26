@extends('layouts.plantilla')

@section('title', 'Utal-Reservas')
@section('content')
    <h1>Bienvenido estudiante: {{ Auth::user()->name }}</h1>

    <button type="button" onclick="window.location='{{ route('register_admin') }}' ">Perfil</button>
    
    <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
    </form>
@endsection