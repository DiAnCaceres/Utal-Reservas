<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CanchaController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImplementoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ModeradorController;
use App\Http\Controllers\ModificarCantidadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\Reservas\PruebasController;
use App\Http\Controllers\SalaEstudioController;
use App\Http\Controllers\SalaGimnasioController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', UsersController::class);

Route::get('/ayuda', function () {
    return view('ayuda');

})->name('ayuda');

Route::get('/dashboard',[UsersController::class, "dashboard"])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/estudiante', [UsersController::class, 'index_estudiante'])->name('estudiante')->middleware('estudiante');
Route::get('/admin', [UsersController::class, 'index_administrador'])->name('admin')->middleware('admin');
Route::get('/moderador', [UsersController::class, 'index_moderador'])->name('moderador')->middleware('moderador');

Route::get('registro_sala_estudio', [SalaEstudioController::class, 'registrar'])->name('registro_sala_estudio')->middleware('admin');
Route::get('/reservar_sala_estudio', [SalaEstudioController::class, 'reservar_seleccionar_fechaBloque'])->name('reservar_sala_estudio');
Route::get('/reservar_salas_estudios_disponibles', [SalaEstudioController::class, 'reservar_salas_disponibles'])->name('reservar_salas_estudios_disponibles');
Route::post("sala_estudio",[SalaEstudioController::class,"store"])->name("registro_sala_estudio.store");
Route::post("reservar_sala_estudio",[SalaEstudioController::class,"reservar"])->name("reservar_sala_estudio.reservar");
Route::post("disponibilidad_sala_estudio",[SalaEstudioController::class,"disponibilidad"])->name("reservar_sala_estudio.disponibilidad");

Route::get('registro_sala_gimnasio', [SalaGimnasioController::class, 'registrar'])->name('registro_sala_gimnasio')->middleware('admin');
Route::get('/reservar_sala_gimnasio', [SalaGimnasioController::class, 'reservar_seleccionar_fechaBloque'])->name('reservar_sala_gimnasio');
Route::get('/reservar_salas_gimnasio_disponibles', [SalaGimnasioController::class, 'reservar_salas_disponibles'])->name('reservar_salas_gimnasio_disponibles');
Route::post("sala_gimnasio",[SalaGimnasioController::class,"store"])->name("registro_sala_gimnasio.store");
Route::post("reservar_sala_gimnasio",[SalaGimnasioController::class,"reservar"])->name("reservar_sala_gimnasio.reservar");
Route::post("disponibilidad_sala_gimnasio",[SalaGimnasioController::class,"disponibilidad"])->name("reservar_sala_gimnasio.disponibilidad");

Route::get('registro_cancha', [CanchaController::class, 'registrar'])->name('registro_cancha')->middleware('admin');
Route::get('/reservar_cancha', [CanchaController::class, 'reservar_seleccionar_fechaBloque'])->name('reservar_cancha');
Route::get('/reservar_canchas_disponibles', [CanchaController::class, 'reservar_canchas_disponibles'])->name('reservar_canchas_disponibles');
Route::post("cancha",[CanchaController::class,"store"])->name("registro_cancha.store");
Route::post("reservar_cancha",[CanchaController::class,"reservar"])->name("reservar_cancha.reservar");
Route::post("disponibilidad_cancha",[CanchaController::class,"disponibilidad"])->name("reservar_cancha.disponibilidad");


Route::get('registro_implemento', [ImplementoController::class, 'implemento'])->name('registro_implemento')->middleware('admin');
Route::get('/reservar_implemento', [ImplementoController::class, 'reservar_seleccionar_fechaBloque'])->name('reservar_implemento');
Route::get('/reservar_implementos_disponibles', [ImplementoController::class, 'reservar_implementos_disponibles'])->name('reservar_implementos_disponibles');
Route::get('/agregar_implemento', [ImplementoController::class, 'agregar_cantidad_implementoExistente'])->name('agregar_implemento');
Route::get('/eliminar_implemento', [ImplementoController::class, 'eliminar_cantidad_implementoExistente'])->name('eliminar_implemento');
Route::post("implemento",[ImplementoController::class,"store"])->name("registro_implemento.store");
Route::post("reservar_implemento",[ImplementoController::class,"reservar"])->name("reservar_implemento.reservar");
Route::post("disponibilidad_implemento",[ImplementoController::class,"disponibilidad"])->name("reservar_implemento.disponibilidad");
