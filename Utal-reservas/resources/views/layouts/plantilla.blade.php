<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} "/>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>

    <!-- header  -->
    <header>
        <img src=" {{asset('img/logo.png')}} " alt="">
        <div class="header-right">
            <p><strong>Universidad de Talca</strong></p>
            @yield('boton-header')
            <!-- if statement para chequear si hay un usuario logueado  -->
            @auth
            <small>Bienvenid@ 
                @switch(Auth::user()->role)
                    @case(1)
                        {{'Admin'}}
                        @break
                
                    @case(2)
                        {{'Moderador'}}
                        @break

                    @case(3)
                        {{'Estudiante'}}
                        @break

                @endswitch
                {{Auth::user()->name }}</small>
            @endauth
            <!-- ------------------------- -->
        </div>
        <span>
        </header>

    @yield('content')
    <!-- footer  -->
    <!-- script  -->
</body>
</html>