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

// Entregar salas de estudio
Route::get('/salaestudio_entregar', [SalaEstudioController::class, 'get_entregar'])->name('salaestudio_entregar')->middleware('moderador');
Route::post('post_salaestudio_entregar', [SalaEstudioController::class, 'post_entregar'])->name('post_salaestudio_entregar')->middleware('moderador');
Route::post('post_salaestudio_entregar_resultados', [SalaEstudioController::class, 'post_entregar_resultados'])->name('post_salaestudio_entregar_resultados')->middleware('moderador');


Route::get('/salagimnasio_registrar', [SalaGimnasioController::class, 'get_registrar'])->name('salagimnasio_registrar')->middleware('admin');
Route::get('/salagimnasio_reservar', [SalaGimnasioController::class, 'get_reservar'])->name('salagimnasio_reservar')->middleware('estudiante');
Route::get('/salagimnasio_reservar_filtrado', [SalaGimnasioController::class, 'get_reservar_filtrado'])->name('salagimnasio_reservar_filtrado')->middleware('estudiante');
Route::post("post_salagimnasio_registrar",[SalaGimnasioController::class,"post_registrar"])->name("post_salagimnasio_registrar");
Route::post("post_salagimnasio_reservar",[SalaGimnasioController::class,"post_reservar"])->name("post_salagimnasio_reservar");
Route::post("post_salagimnasio_reservar_filtrado",[SalaGimnasioController::class,"post_reservar_filtrado"])->name("post_salagimnasio_reservar_filtrado");

// Entregar salas de gimnasio
Route::get('/salagimnasio_entregar', [SalaGimnasioController::class, 'get_entregar'])->name('salagimnasio_entregar')->middleware('moderador');

Route::post('post_salagimnasio_entregar', [SalaGimnasioController::class, 'post_entregar'])->name('post_salagimnasio_entregar');

Route::post('post_salagimnasio_entregar_resultados', [SalaGimnasioController::class, 'post_entregar_resultados'])->name('post_salagimnasio_entregar_resultados');


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

// rutas semana 4


/*-----------------_RU07: Cancelar reserva ---------------*/
Route::get('/salaestudio_cancelar', [SalaEstudioController::class, 'get_cancelar'])->name('salaestudio_cancelar')->middleware('estudiante');

Route::post('post_salaestudio_cancelar', [SalaEstudioController::class, 'post_cancelar'])->name('post_salaestudio_cancelar')->middleware('estudiante');

/*-----------------_RU08: Entregar sala estudio  ---------------*/
Route::get('/salaestudio_entregar', [SalaEstudioController::class, 'get_entregar'])->name('salaestudio_entregar')->middleware('moderador');

Route::post('post_salaestudio_entregar', [SalaEstudioController::class, 'post_entregar'])->name('post_salaestudio_entregar')->middleware('moderador');

Route::post('post_salaestudio_entregar_resultados', [SalaEstudioController::class, 'post_entregar_resultados'])->name('post_salaestudio_entregar_resultados')->middleware('moderador');

Route::get('/salaestudio_entregar_filtrado', [SalaEstudioController::class, 'get_entregar_filtrado'])->name('salaestudio_entregar_filtrado')->middleware('moderador');

route::post('post_salaestudio_entregar_filtrado', [SalaEstudioController::class, 'post_entregar_filtrado'])->name('post_salaestudio_entregar_filtrado')->middleware('moderador');

/*-----------------_RU09: Recepcionar sala estudio ---------------*/
Route::get('/salaestudio_recepcionar', [SalaEstudioController::class, 'get_recepcionar'])->name('salaestudio_recepcionar')->middleware('moderador');

Route::post('post_salaestudio_recepcionar', [SalaEstudioController::class, 'post_recepcionar'])->name('post_salaestudio_recepcionar')->middleware('moderador');

Route::post('post_salaestudio_recepcionar_resultados', [SalaEstudioController::class, 'post_recepcionar_resultados'])->name('post_salaestudio_recepcionar_resultados')->middleware('moderador');

Route::get('/salaestudio_recepcionar_filtrado', [SalaEstudioController::class, 'get_recepcionar_filtrado'])->name('salaestudio_recepcionar_filtrado')->middleware('moderador');

Route::post('post_salaestudio_recepcionar_filtrado', [SalaEstudioController::class, 'post_recepcionar_filtrado'])->name('post_salaestudio_recepcionar_filtrado')->middleware('moderador');


/*-----------------_RU10: Recepcionar sala gimnasio ---------------*/
Route::get('/salagimnasio_recepcionar', [SalaGimnasioController::class, 'get_recepcionar'])->name('salagimnasio_recepcionar')->middleware('moderador');

Route::post('post_salagimnasio_recepcionar', [SalagimnasioController::class, 'post_recepcionar'])->name('post_salagimnasio_recepcionar')->middleware('moderador');

Route::post('post_salagimnasio_recepcionar_resultados', [SalaGimnasioController::class, 'post_recepcionar_resultados'])->name('post_salagimnasio_recepcionar_resultados')->middleware('moderador');

Route::get('/salagimnasio_recepcionar_filtrado', [SalaGimnasioController::class, 'get_recepcionar_filtrado'])->name('salagimnasio_recepcionar_filtrado')->middleware('moderador');

Route::post('post_salagimnasio_recepcionar_filtrado', [SalaGimnasioController::class, 'post_recepcionar_filtrado'])->name('post_salagimnasio_recepcionar_filtrado')->middleware('moderador');


/*-----------------_RU13: Cancelar sala gimnasio ---------------*/
route::get('/salagimnasio_cancelar', [SalaGimnasioController::class, 'get_cancelar'])->name('salagimnasio_cancelar')->middleware('estudiante');

Route::post('post_salagimnasio_cancelar', [SalaGimnasioController::class, 'post_cancelar'])->name('post_salagimnasio_cancelar')->middleware('estudiante');

/*----------------- RU14: Entregar sala gimnasio ---------------*/
Route::get('/salagimnasio_entregar', [SalaGimnasioController::class, 'get_entregar'])->name('salagimnasio_entregar')->middleware('moderador');

Route::post('post_salagimnasio_entregar', [SalaGimnasioController::class, 'post_entregar'])->name('post_salagimnasio_entregar')->middleware('moderador');

Route::post('post_salagimnasio_entregar_resultados', [SalaGimnasioController::class, 'post_entregar_resultados'])->name('post_salagimnasio_entregar_resultados')->middleware('moderador');

Route::get('/salagimnasio_entregar_filtrado', [SalaGimnasioController::class, 'get_entregar_filtrado'])->name('salagimnasio_entregar_filtrado')->middleware('moderador')

;Route::post('post_salagimnasio_entregar_filtrado', [SalaGimnasioController::class, 'post_entregar_filtrado'])->name('post_salagimnasio_entregar_filtrado')->middleware('moderador');


/*-----------------  RU19 - Cancelar reserva cancha ---------------*/
Route::get('/cancha_cancelar', [CanchaController::class, 'get_cancelar'])->name('cancha_cancelar')->middleware('estudiante');

Route::post('post_cancha_cancelar', [CanchaController::class, 'post_cancelar'])->name('post_cancha_cancelar')->middleware('estudiante');

/*-----------------  RU19 - Entregar cancha ---------------*/

Route::get('/cancha_entregar', [CanchaController::class, 'get_entregar'])->name('cancha_entregar')->middleware('moderador');

Route::post('post_cancha_entregar', [CanchaController::class, 'post_entregar'])->name('post_cancha_entregar')->middleware('moderador');

Route::post('post_cancha_entregar_resultados', [CanchaController::class, 'post_entregar_resultados'])->name('post_cancha_entregar_resultados')->middleware('moderador');

Route::get('/cancha_entregar_filtrado', [CanchaController::class, 'get_entregar_filtrado'])->name('cancha_entregar_filtrado')->middleware('moderador');

Route::post('post_cancha_entregar_filtrado', [CanchaController::class, 'post_entregar_filtrado'])->name('post_cancha_entregar_filtrado')->middleware('moderador');

/*-----------------  RU27 - Cancelar implemento ---------------*/

Route::get('/implemento_cancelar', [ImplementoController::class, 'get_cancelar'])->name('implemento_cancelar')->middleware('estudiante');

Route::post('post_implemento_cancelar', [ImplementoController::class, 'post_cancelar'])->name('post_implemento_cancelar')->middleware('estudiante');

/*-----------------  RU28 - Entregar implemento ---------------*/
Route::get('/implemento_entregar', [ImplementoController::class, 'get_entregar'])->name('implemento_entregar')->middleware('moderador');

Route::post('post_implemento_entregar', [ImplementoController::class, 'post_entregar'])->name('post_implemento_entregar')->middleware('moderador');

Route::post('post_implemento_entregar_resultados', [ImplementoController::class, 'post_entregar_resultados'])->name('post_implemento_entregar_resultados')->middleware('moderador');

Route::get('/implemento_entregar_filtrado', [ImplementoController::class, 'get_entregar_filtrado'])->name('implemento_entregar_filtrado')->middleware('moderador');

Route::post('post_implemento_entregar_filtrado', [ImplementoController::class, 'post_entregar_filtrado'])->name('post_implemento_entregar_filtrado')->middleware('moderador');

/* ------------------ SEMANA 5 -------------------------------*/
/* Recepcionar cancha*/
Route::get('/cancha_recepcionar', [CanchaController::class, 'get_recepcionar'])->name('cancha_recepcionar')->middleware('moderador');

Route::post('post_cancha_recepcionar', [CanchaController::class, 'post_recepcionar'])->name('post_cancha_recepcionar')->middleware('moderador');

Route::post('post_cancha_recepcionar_resultados', [CanchaController::class, 'post_recepcionar_resultados'])->name('post_cancha_recepcionar_resultados')->middleware('moderador');

/* Recepcionar implemento*/
Route::get('/implemento_recepcionar', [ImplementoController::class, 'get_recepcionar'])->name('implemento_recepcionar')->middleware('moderador');

Route::post('post_implemento_recepcionar', [ImplementoController::class, 'post_recepcionar'])->name('post_implemento_recepcionar')->middleware('moderador');

Route::post('post_implemento_recepcionar_resultados', [ImplementoController::class, 'post_recepcionar_resultados'])->name('post_implemento_recepcionar_resultados')->middleware('moderador');

/* Deshabilitar sala estudio*/
Route::get('/salaestudio_deshabilitar', [SalaEstudioController::class, 'get_deshabilitar'])->name('salaestudio_deshabilitar')->middleware('admin');

Route::post('post_salaestudio_deshabilitar', [SalaEstudioController::class, 'post_deshabilitar'])->name('post_salaestudio_deshabilitar')->middleware('admin');

/* Deshabilitar sala gimnasio*/
Route::get('/salagimnasio_deshabilitar', [SalaGimnasioController::class, 'get_deshabilitar'])->name('salagimnasio_deshabilitar')->middleware('admin');

Route::post('post_salagimnasio_deshabilitar', [SalaGimnasioController::class, 'post_deshabilitar'])->name('post_salagimnasio_deshabilitar')->middleware('admin');

/* Deshabilitar cancha*/
Route::get('/cancha_deshabilitar', [CanchaController::class, 'get_deshabilitar'])->name('cancha_deshabilitar')->middleware('admin');

Route::post('post_cancha_deshabilitar', [CanchaController::class, 'post_deshabilitar'])->name('post_cancha_deshabilitar')->middleware('admin');

/* Implemento cancha*/
Route::get('/implemento_deshabilitar', [ImplementoController::class, 'get_deshabilitar'])->name('implemento_deshabilitar')->middleware('admin');

Route::post('post_implemento_deshabilitar', [ImplementoController::class, 'post_deshabilitar'])->name('post_implemento_deshabilitar')->middleware('admin');


