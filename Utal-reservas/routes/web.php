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

Route::get('registro', [RegistroController::class, 'registro_sala'])->name('registro_btn');

Route::get('/', HomeController::class);

// Route::get('/', HomeController::class, 'login');

Route::get('login', [LoginController::class, 'index'])->name('login');

Route::get('/ayuda', function () {
    return view('ayuda');
})->name('ayuda');

