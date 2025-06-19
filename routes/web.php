<?php

use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\HistorialMedicoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecordatorioController;
use App\Http\Controllers\TemaController;
use App\Http\Controllers\SoporteController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\CanEditUser;
Route::post('/modo-oscuro', [App\Http\Controllers\TemaController::class, 'toggle'])->name('modo-oscuro.toggle');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas solo para admin (todas las funciones de usuarios)
Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::resource('usuarios', UserController::class)->except(['show', 'edit', 'update']);
});
// Rutas para que cualquier usuario vea o edite su perfil
Route::middleware(['auth', CanEditUser::class])->group(function () {
    Route::get('usuarios/{usuario}', [UserController::class, 'show'])->name('usuarios.show');
    Route::get('usuarios/{usuario}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('usuarios/{usuario}', [UserController::class, 'update'])->name('usuarios.update');
});
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::resource('mascotas.historial', HistorialMedicoController::class);
Route::resource('usuarios.mascotas', MascotaController::class);
Route::resource('mascotas', MascotaController::class);
// Ruta anidada: mascotas/{mascota}/recordatorios
Route::resource('mascotas.recordatorios', RecordatorioController::class);

// Ruta de recordatorios personales (OK)
Route::get('recordatorios/personales/{usuario}', [RecordatorioController::class, 'personales'])
    ->middleware('auth')->name('recordatorios.personales');

// Ruta del calendario de recordatorios
Route::get('recordatorios/calendario/{usuario}', [RecordatorioController::class, 'calendario'])
    ->middleware('auth')->name('recordatorios.calendario');

// Crear recordatorio con mascota opcional
Route::get('recordatorios/create', [RecordatorioController::class, 'create'])
    ->name('recordatorios.create');


// Ruta para marcar como visto (OK)
Route::patch('/recordatorios/{recordatorio}/visto', [RecordatorioController::class, 'marcarComoVisto'])
    ->name('recordatorios.visto');

// Ruta para cambiar el estado de un recordatorio (realizado/pendiente)
Route::patch('/recordatorios/{recordatorio}/cambiar-estado', [RecordatorioController::class, 'cambiarEstado'])->name('recordatorios.cambiarEstado');

// Rutas normales para /recordatorios (posible conflicto)
Route::resource('recordatorios', RecordatorioController::class);

// Rutas de soporte
Route::middleware(['auth'])->group(function () {
    Route::get('/soporte/contacto', [SoporteController::class, 'mostrarFormulario'])->name('soporte.contacto');
    Route::post('/soporte/enviar', [SoporteController::class, 'enviarMensaje'])->name('soporte.enviar');
});

Route::get('/sing', function () {
    return view('sign-in');
});

// Rutas para recuperación de contraseña
Route::get('forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

Route::view('/about', 'about')->name('about');
Route::view('/cookies', 'cookies')->name('cookies');

// Ruta para ver el índice global del historial médico
Route::get('historial', [HistorialMedicoController::class, 'index'])->name('historial.index');



