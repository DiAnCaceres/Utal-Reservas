<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistroController;

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

Route::get('/', HomeController::class);

// Route::get('/', HomeController::class, 'login');

Route::get('login', [LoginController::class, 'index'])->name('login');

Route::get('/ayuda', function () {
    return view('ayuda');

})->name('ayuda');


Route::get('registro_sala_estudio', [RegistroController::class, 'sala_estudio'])->name('registro_sala_estudio');
Route::get('registro_sala_gimnasio', [RegistroController::class, 'sala_gimnasio'])->name('registro_sala_gimnasio');
Route::get('registro_cancha', [RegistroController::class, 'cancha'])->name('registro_cancha');
Route::get('registro_implemento', [RegistroController::class, 'implemento'])->name('registro_implemento');