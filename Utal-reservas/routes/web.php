<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ModeradorController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\Reservas\CanchaController;
use App\Http\Controllers\Reservas\ImplementoController;
use App\Http\Controllers\Reservas\SalaEstudioController;
use App\Http\Controllers\Reservas\SalaGimnasioController;

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

// Route::get('registro', [RegistroController::class, 'registro_sala'])->name('registro_btn');

Route::get('/', HomeController::class);

// Route::get('/', HomeController::class, 'login');

//Route::get('login', [LoginController::class, 'index'])->name('login');

Route::get('/ayuda', function () {
    return view('ayuda');

})->name('ayuda');

/*Route::get('home_estudiante', [HomeController::class, 'home_estudiante'])->name('home_estudiante');
Route::get('home_moderador', [HomeController::class, 'home_moderador'])->name('home_moderador');
Route::get('home_admin', [HomeController::class, 'home_admin'])->name('home_admin');
*/

Route::get('registro_sala_estudio', [RegistroController::class, 'sala_estudio'])->name('registro_sala_estudio')->middleware('admin');
Route::get('registro_sala_gimnasio', [RegistroController::class, 'sala_gimnasio'])->name('registro_sala_gimnasio')->middleware('admin');
Route::get('registro_cancha', [RegistroController::class, 'cancha'])->name('registro_cancha')->middleware('admin');
Route::get('registro_implemento', [RegistroController::class, 'implemento'])->name('registro_implemento')->middleware('admin');

Route::post("registro_sala_estudio",[SalaEstudioController::class,"store"])->name("registro_sala_estudio.store");
Route::post("registro_cancha",[CanchaController::class,"store"])->name("registro_cancha.store");
Route::post("registro_sala_gimnasio",[SalaGimnasioController::class,"store"])->name("registro_sala_gimnasio.store");
Route::post("registro_implemento",[ImplementoController::class,"store"])->name("registro_implemento.store");
/*---------------------*/
/*Route::get('registro_estudiante', [RegistroController::class, 'estudiante'])->name('registro_estudiante');
Route::get('registro_moderador', [RegistroController::class, 'moderador'])->name('registro_moderador');
Route::get('registro_admin', [RegistroController::class, 'admin'])->name('registro_admin');*/
/*---------------------*/
//------------------------------------------------------------
// Route::get('/welcome', function () {
//     return view('welcome');
// });

Route::get('/dashboard',[HomeController::class, "dashboard"])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/estudiante', [EstudianteController::class, 'index'])->name('estudiante')->middleware('estudiante');
Route::get('/admin', [AdminController::class, 'index'])->name('admin')->middleware('admin');
Route::get('/moderador', [ModeradorController::class, 'index'])->name('moderador')->middleware('moderador');
require __DIR__.'/auth.php';