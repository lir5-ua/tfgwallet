<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\HistorialMedicoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecordatorioController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', function () {
    return view('landing');
});

Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::resource('usuarios', UserController::class);
});
Route::resource('mascotas.historial', HistorialMedicoController::class);
Route::resource('usuarios.mascotas', MascotaController::class);
Route::resource('mascotas', MascotaController::class);
Route::resource('mascotas.recordatorios',RecordatorioController::class);
Route::resource('recordatorios', RecordatorioController::class);
Route::patch('/recordatorios/{recordatorio}/visto', [RecordatorioController::class, 'marcarComoVisto'])->name('recordatorios.visto');


