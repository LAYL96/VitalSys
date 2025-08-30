<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

/*
Route::middleware(['auth', 'role:Administrador'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class)->except(['show']);
});
*/

// Rutas para autenticados
Route::middleware(['auth'])->group(function () {

    // Ruta de administración de usuarios solo para administradores
    Route::get('/admin/users', [UserController::class, 'index'])
        ->middleware(RoleMiddleware::class . ':Administrador')
        ->name('admin.users');

    // Puedes agregar otras rutas protegidas por roles
    // Route::get('/empleado/dashboard', [EmpleadoController::class, 'index'])
    //     ->middleware(RoleMiddleware::class . ':Empleado')
    //     ->name('empleado.dashboard');
});

Route::middleware(['auth', 'role:Empleado'])->group(function () {
    Route::get('/empleado', function () {
        return "Bienvenido Empleado";
    });
});

Route::middleware(['auth', 'role:Médico'])->group(function () {
    Route::get('/medico', function () {
        return "Bienvenido Médico";
    });
});

Route::middleware(['auth', 'role:Cliente'])->group(function () {
    Route::get('/cliente', function () {
        return "Bienvenido Cliente";
    });
});
