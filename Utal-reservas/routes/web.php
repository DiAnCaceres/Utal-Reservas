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

Route::get('/ayuda', function () {
    return view('ayuda');

})->name('ayuda');

Route::get('/', UsersController::class); // <-- ruta default
// ruta para redireccionar cuando cierran sesión
Route::get('/usuario_redireccionar',[UsersController::class, "get_redireccionar"])->middleware(['auth', 'verified'])->name('usuario_redireccionar');
// rutas posterior a inicio de sesión
Route::get('/usuario_menuestudiante', [UsersController::class, 'get_menuestudiante'])->name('estudiante')->middleware('usuario_estudiante');
Route::get('/usuario_menuadministrador', [UsersController::class, 'get_menuadministrador'])->name('admin')->middleware('usuario_administrador');
Route::get('/usuario_menumoderador', [UsersController::class, 'get_menumoderador'])->name('moderador')->middleware('usuario_menumoderador');
require __DIR__.'/auth.php';

Route::get('/salaestudio_registrar', [SalaEstudioController::class, 'get_registrar'])->name('salaestudio_registrar')->middleware('admin');
Route::get('/salaestudio_reservar', [SalaEstudioController::class, 'get_reservar'])->name('salaestudio_reservar');
Route::get('/salaestudio_reservar_filtrado', [SalaEstudioController::class, 'get_reservar_filtrado'])->name('salaestudio_reservar_filtrado');
Route::post("/post_salaestudio_registrar",[SalaEstudioController::class,"post_registrar"])->name("post_salaestudio_registrar");
Route::post("/post_salaestudio_reservar",[SalaEstudioController::class,"post_reservar"])->name("post_salaestudio_reservar");
Route::post("/post_salaestudio_reservar_filtrado",[SalaEstudioController::class,"post_reservar_filtrado"])->name("post_salaestudio_reservar_filtrado");

Route::get('/salagimnasio_registrar', [SalaGimnasioController::class, 'get_registrar'])->name('registro_sala_gimnasio')->middleware('admin');
Route::get('/salagimnasio_reservar', [SalaGimnasioController::class, 'get_reservar'])->name('reservar_sala_gimnasio');
Route::get('/salagimnasio_reservar_filtrado', [SalaGimnasioController::class, 'get_reservar_filtrado'])->name('reservar_salas_gimnasio_disponibles');
Route::post("/post_salagimnasio_registrar",[SalaGimnasioController::class,"post_registrar"])->name("registro_sala_gimnasio.store");
Route::post("/post_salagimnasio_reservar",[SalaGimnasioController::class,"post_reservar"])->name("reservar_sala_gimnasio.reservar");
Route::post("/post_salagimnasio_reservar_filtrado",[SalaGimnasioController::class,"post_reservar_filtrado"])->name("reservar_sala_gimnasio.disponibilidad");

Route::get('/cancha_registrar', [CanchaController::class, 'get_registrar'])->name('registro_cancha')->middleware('admin');
Route::get('/cancha_reservar', [CanchaController::class, 'get_reservar'])->name('reservar_cancha');
Route::get('/cancha_reservar_filtrado', [CanchaController::class, 'get_reservar_filtrado'])->name('reservar_canchas_disponibles');
Route::post("/post_cancha_registrar",[CanchaController::class,"post_registrar"])->name("registro_cancha.store");
Route::post("/post_cancha_reservar",[CanchaController::class,"post_reservar"])->name("reservar_cancha.reservar");
Route::post("/post_cancha_reservar_filtrado",[CanchaController::class,"post_reservar_filtrado"])->name("reservar_cancha.disponibilidad");

Route::get('/implemento_registrar', [ImplementoController::class, 'get_registrar'])->name('registro_implemento')->middleware('admin');
Route::get('/implemento_reservar', [ImplementoController::class, 'get_reservar'])->name('reservar_implemento');
Route::get('/implemento_reservar_filtrado', [ImplementoController::class, 'get_reservar_filtrado'])->name('reservar_implementos_disponibles');
Route::get('/implemento_modificarcantidad_agregar', [ImplementoController::class, 'get_modificarcantidad_agregar'])->name('agregar_implemento');
Route::get('/implemento_modificarcantidad_eliminar', [ImplementoController::class, 'get_modificarcantidad_eliminar'])->name('eliminar_implemento');
Route::post("/post_implemento_registrar",[ImplementoController::class,"post_registrar"])->name("registro_implemento.store");
Route::post("/post_implemento_reservar",[ImplementoController::class,"post_reservar"])->name("reservar_implemento.reservar");
Route::post("/post_implemento_reservar_filtrado",[ImplementoController::class,"post_reservar_filtrado"])->name("reservar_implemento.disponibilidad");
