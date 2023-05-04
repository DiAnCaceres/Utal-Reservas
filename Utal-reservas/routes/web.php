<?php


use App\Http\Controllers\CanchaController;
use App\Http\Controllers\ImplementoController;
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
Route::get('/usuario_menuestudiante', [UsersController::class, 'get_menuestudiante'])->name('usuario_menuestudiante')->middleware('estudiante');
Route::get('/usuario_menuadministrador', [UsersController::class, 'get_menuadministrador'])->name('usuario_menuadministrador')->middleware('admin');
Route::get('/usuario_menumoderador', [UsersController::class, 'get_menumoderador'])->name('usuario_menumoderador')->middleware('moderador');
require __DIR__.'/auth.php';

Route::get('/salaestudio_registrar', [SalaEstudioController::class, 'get_registrar'])->name('salaestudio_registrar')->middleware('admin');
Route::get('/salaestudio_reservar', [SalaEstudioController::class, 'get_reservar'])->name('salaestudio_reservar')->middleware('estudiante');
Route::get('/salaestudio_reservar_filtrado', [SalaEstudioController::class, 'get_reservar_filtrado'])->name('salaestudio_reservar_filtrado')->middleware('estudiante');
Route::post("post_salaestudio_registrar",[SalaEstudioController::class,"post_registrar"])->name("post_salaestudio_registrar");
Route::post("post_salaestudio_reservar",[SalaEstudioController::class,"post_reservar"])->name("post_salaestudio_reservar");
Route::post("post_salaestudio_reservar_filtrado",[SalaEstudioController::class,"post_reservar_filtrado"])->name("post_salaestudio_reservar_filtrado");

Route::get('/salagimnasio_registrar', [SalaGimnasioController::class, 'get_registrar'])->name('salagimnasio_registrar')->middleware('admin');
Route::get('/salagimnasio_reservar', [SalaGimnasioController::class, 'get_reservar'])->name('salagimnasio_reservar')->middleware('estudiante');
Route::get('/salagimnasio_reservar_filtrado', [SalaGimnasioController::class, 'get_reservar_filtrado'])->name('salagimnasio_reservar_filtrado')->middleware('estudiante');
Route::post("post_salagimnasio_registrar",[SalaGimnasioController::class,"post_registrar"])->name("post_salagimnasio_registrar");
Route::post("post_salagimnasio_reservar",[SalaGimnasioController::class,"post_reservar"])->name("post_salagimnasio_reservar");
Route::post("post_salagimnasio_reservar_filtrado",[SalaGimnasioController::class,"post_reservar_filtrado"])->name("post_salagimnasio_reservar_filtrado");

Route::get('/cancha_registrar', [CanchaController::class, 'get_registrar'])->name('cancha_registrar')->middleware('admin');
Route::get('/cancha_reservar', [CanchaController::class, 'get_reservar'])->name('cancha_reservar')->middleware('estudiante');
Route::get('/cancha_reservar_filtrado', [CanchaController::class, 'get_reservar_filtrado'])->name('cancha_reservar_filtrado')->middleware('estudiante');
Route::post("post_cancha_registrar",[CanchaController::class,"post_registrar"])->name("post_cancha_registrar");
Route::post("post_cancha_reservar",[CanchaController::class,"post_reservar"])->name("post_cancha_reservar");
Route::post("post_cancha_reservar_filtrado",[CanchaController::class,"post_reservar_filtrado"])->name("post_cancha_reservar_filtrado");

Route::get('/implemento_registrar', [ImplementoController::class, 'get_registrar'])->name('implemento_registrar')->middleware('admin');
Route::get('/implemento_reservar', [ImplementoController::class, 'get_reservar'])->name('implemento_reservar')->middleware('estudiante');
Route::get('/implemento_reservar_filtrado', [ImplementoController::class, 'get_reservar_filtrado'])->name('implemento_reservar_filtrado')->middleware('estudiante');
Route::get('/implemento_modificarcantidad_agregar', [ImplementoController::class, 'get_modificarcantidad_agregar'])->name('implemento_modificarcantidad_agregar')->middleware('moderador');
Route::get('/implemento_modificarcantidad_eliminar', [ImplementoController::class, 'get_modificarcantidad_eliminar'])->name('implemento_modificarcantidad_eliminar')->middleware('moderador');
Route::post("post_implemento_registrar",[ImplementoController::class,"post_registrar"])->name("post_implemento_registrar");
Route::post("post_implemento_reservar", [ImplementoController::class, "post_reservar"])->name("post_implemento_reservar");
Route::post("post_implemento_reservar_filtrado",[ImplementoController::class, "post_reservar_filtrado"])->name("post_implemento_reservar_filtrado");
Route::post('/implemento_modificarcantidad_agregar', [ImplementoController::class, 'post_modificarcantidad_agregar'])->name('implemento_modificarcantidad_agregar');
Route::post('/implemento_modificarcantidad_eliminar', [ImplementoController::class, 'post_modificarcantidad_eliminar'])->name('implemento_modificarcantidad_eliminar');




Route::post("/login",[UsersController::class, "post_login"])->name("post_login");