<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\HistorialMedicoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', function () {
    return view('landing');
});

Route::resource('usuarios', UserController::class);
Route::resource('mascotas.historial', HistorialMedicoController::class);
Route::resource('mascotas', MascotaController::class);
