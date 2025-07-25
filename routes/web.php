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
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Middleware\CheckUltimaConexion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Http\Controllers\VeterinarioController;
use App\Http\Controllers\Auth\VeterinarioAuthController;

Route::post('/modo-oscuro', [App\Http\Controllers\TemaController::class, 'toggle'])->name('modo-oscuro.toggle');

Route::post('/settings/toggle-email-notification', [UserController::class, 'toggleEmailNotification'])
    ->middleware(['auth', App\Http\Middleware\CheckUltimaConexion::class])
    ->name('settings.toggle-email-notification');
    Route::get('mascotas/{mascota_hashid}/historial/create', [HistorialMedicoController::class, 'create'])->name('mascotas.historial.create');
Route::get('/login', function () {
    return view('sign-in');
})->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas solo para admin (todas las funciones de usuarios)
Route::middleware(['auth', CheckUltimaConexion::class,IsAdmin::class])->group(function () {
    Route::resource('usuarios', UserController::class)->except(['show', 'edit', 'update']);
});
// Rutas para que cualquier usuario vea o edite su perfil
Route::middleware(['auth',CheckUltimaConexion::class, CanEditUser::class])->group(function () {
    Route::get('usuarios/{usuario}', [UserController::class, 'show'])->name('usuarios.show');
    Route::get('usuarios/{usuario}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('usuarios/{usuario}', [UserController::class, 'update'])->name('usuarios.update');
});
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('mascotas/{mascota_hashid}/historial/{historial_hashid}', [HistorialMedicoController::class, 'show'])
    ->name('mascotas.historial.show');
Route::get('mascotas/{mascota_hashid}/historial/{historial_hashid}/edit', [HistorialMedicoController::class, 'edit'])
->name('mascotas.historial.edit');
Route::put('mascotas/{mascota_hashid}/historial/{historial_hashid}', [HistorialMedicoController::class, 'update'])->name('mascotas.historial.update');

Route::post('mascotas/{mascota_hashid}/historial', [HistorialMedicoController::class, 'store'])->name('mascotas.historial.store');
Route::delete('mascotas/{mascota_hashid}/historial/{historial_hashid}', [HistorialMedicoController::class, 'destroy'])->name('mascotas.historial.destroy');
Route::get('mascotas/{mascota_hashid}/historial', [HistorialMedicoController::class, 'index'])->name('mascotas.historial.index');


//Route::resource('usuarios.mascotas', MascotaController::class);
//Route::get('mascotas/{hashid}', [MascotaController::class, 'show'])->name('mascotas.show');
//Route::get('mascotas/{hashid}/edit', [MascotaController::class, 'edit'])->name('mascotas.edit');
//Route::put('mascotas/{hashid}', [MascotaController::class, 'update'])->name('mascotas.update');
Route::post('usuarios/{usuario_hashid}/mascotas', [MascotaController::class, 'store'])
->name('usuarios.mascotas.store');
Route::put('usuarios/{usuario_hashid}/mascotas/{mascota_hashid}', [MascotaController::class, 'update'])
->name('usuarios.mascotas.update');
Route::patch('usuarios/{usuario_hashid}/mascotas/{mascota_hashid}', [MascotaController::class, 'update']); // Optional: Add patch for update

Route::delete('mascotas/{hashid}', [MascotaController::class, 'destroy'])->name('mascotas.destroy');
Route::get('mascotas', [App\Http\Controllers\MascotaController::class, 'index'])->name('mascotas.index');
// Ruta anidada: mascotas/{mascota}/recordatorios
Route::resource('mascotas', MascotaController::class)->parameters([
    'mascotas' => 'hashid',
]);

Route::resource('mascotas.recordatorios', RecordatorioController::class)->parameters([
    'mascotas' => 'hashid',
    'recordatorios' => 'hashid',
]);

Route::resource('recordatorios', RecordatorioController::class)->parameters([
    'recordatorios' => 'hashid',
]);
// Eliminar o comentar la ruta antigua:
 /*Route::get('recordatorios/personales/{usuario}', [RecordatorioController::class, 'personales'])
     ->middleware(['auth', 'verified'])->name('recordatorios.personales');
*/
Route::get('recordatorios/calendario/{usuario}', [RecordatorioController::class, 'calendario'])
    ->middleware('auth')->name('recordatorios.calendario');

Route::get('recordatorios/create', [RecordatorioController::class, 'create'])
    ->name('recordatorios.create');

Route::patch('/recordatorios/{recordatorio}/visto', [RecordatorioController::class, 'marcarComoVisto'])
    ->name('recordatorios.visto');

Route::patch('/recordatorios/{recordatorio}/cambiar-estado', [RecordatorioController::class, 'cambiarEstado'])
    ->name('recordatorios.cambiarEstado');
    


// Rutas de soporte
Route::middleware(['auth'])->group(function () {
    Route::get('/soporte/contacto', [SoporteController::class, 'mostrarFormulario'])->name('soporte.contacto');
    Route::post('/soporte/enviar', [SoporteController::class, 'enviarMensaje'])->name('soporte.enviar');
});

Route::get('/sing', function () {
    return view('sign-in');
});

// Rutas para recuperación de contraseña
Route::get('password/forgot', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

Route::view('/about', 'about')->name('about');
Route::view('/cookies', 'cookies')->name('cookies');

// Ruta para ver el índice global del historial médico
Route::get('historial', [HistorialMedicoController::class, 'index'])->name('historial.index');

Route::post('mascotas', [App\Http\Controllers\MascotaController::class, 'store'])->name('mascotas.store');

// Rutas de verificación de email
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    $user = \App\Models\User::findOrFail($id);
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403);
    }
    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        $user->ultima_conexion = now();
        $user->save();
    }
    if (is_null($user->ultima_conexion)) {
        $user->ultima_conexion = now();
        $user->save();
    }
    Auth::login($user);
    return redirect()->route('usuarios.show', $user);
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '¡Enlace de verificación reenviado!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::post('/usuarios/toggle-web-notifications', [UserController::class, 'toggleWebNotifications'])->middleware(['auth', App\Http\Middleware\CheckUltimaConexion::class])->name('usuarios.toggle-web-notifications');

// Rutas explícitas para MascotaController
Route::get('mascotas', [MascotaController::class, 'index'])->name('mascotas.index');
Route::get('mascotas/create', [MascotaController::class, 'create'])->name('mascotas.create');
Route::post('mascotas', [MascotaController::class, 'store'])->name('mascotas.store');
Route::get('mascotas/{hashid}', [MascotaController::class, 'show'])->name('mascotas.show');
Route::get('mascotas/{hashid}/edit', [MascotaController::class, 'edit'])->name('mascotas.edit');
Route::put('mascotas/{hashid}', [MascotaController::class, 'update'])->name('mascotas.updatee');
Route::patch('mascotas/{hashid}', [MascotaController::class, 'update']);
Route::delete('mascotas/{hashid}', [MascotaController::class, 'destroy'])->name('mascotas.destroy');
Route::post('mascotas/{hashid}/generar-codigo', [MascotaController::class, 'generateAccessCode'])->name('mascotas.generar-codigo');

Route::post('acceso/historial', [HistorialMedicoController::class, 'accederPorCodigo'])->name('acceso.historial');
Route::get('acceso/historial/form', function() {
    return view('veterinarios.acceso-codigo');
})->middleware('auth:veterinarios')->name('acceso.historial.form');

// Rutas para veterinarios
Route::get('veterinarios', [VeterinarioController::class, 'index'])->name('veterinarios.index');
Route::post('veterinarios', [VeterinarioController::class, 'store'])->name('veterinarios.store');

// Rutas de autenticación y perfil para veterinarios
Route::get('veterinarios/login', [VeterinarioAuthController::class, 'showLoginForm'])->name('veterinarios.login');
Route::post('veterinarios/login', [VeterinarioAuthController::class, 'login'])->name('veterinarios.login.submit');
Route::post('veterinarios/logout', [VeterinarioAuthController::class, 'logout'])->name('veterinarios.logout');
Route::get('veterinarios/{veterinario}/perfil', [App\Http\Controllers\VeterinarioController::class, 'show'])->name('veterinarios.perfil');





