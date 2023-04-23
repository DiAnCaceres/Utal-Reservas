@extends('layouts.plantilla')

@section('title')

@section('content')

    <div class="contenedor">

        <div class="box_1_ligteblue">

        </div>
    </div>

    <div class="separacion">

    </div>

    <div class="contenedor">

        <div class="login">

            <form action="">

                <input type="text" placeholder="Rut">
                <input type="password" placeholder="Contraseña">
                <button class="btnEntrar">Entrar<i class="ri-arrow-right-line"></i></button>

               

                <form action="" method="POST">
                    @csrf

                    <input type="text" name="name" placeholder="Enter Name" required>  
                    <br><br>
                    <select name="" multiple="multiple" required>
                        <option >Select Hobby</option>
                        <option Cricket">Cricket</option>
                        <option Singing" >Singing</option>
                        <option Playing" >Playing</option>
                        <option Listening”>Listening</option>
                        <option Travelling">Travelling</option>
                    </select>
                    <br><br>
                    <input type="submit">
                </form>


            </form>
        </div>
    </div>



@endsection