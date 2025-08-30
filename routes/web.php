<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

// Página de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (requiere autenticación y email verificado)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas de perfil (solo usuarios autenticados)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Autenticación generada por Laravel Breeze / Jetstream
require __DIR__ . '/auth.php';

// ===========================================
// Rutas de administración de usuarios (Administrador)
// ===========================================
Route::middleware(['auth', RoleMiddleware::class . ':Administrador'])->prefix('admin')->name('admin.')->group(function () {

    // Listado de usuarios
    Route::get('/users', [UserController::class, 'index'])->name('users');

    // Crear usuario
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    // Editar usuario
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    // Eliminar usuario
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// ===========================================
// Rutas específicas para otros roles
// ===========================================

// Empleado
Route::middleware(['auth', RoleMiddleware::class . ':Empleado'])->group(function () {
    Route::get('/empleado', function () {
        return "Bienvenido Empleado";
    });
});

// Médico
Route::middleware(['auth', RoleMiddleware::class . ':Médico'])->group(function () {
    Route::get('/medico', function () {
        return "Bienvenido Médico";
    });
});

// Cliente
Route::middleware(['auth', RoleMiddleware::class . ':Cliente'])->group(function () {
    Route::get('/cliente', function () {
        return "Bienvenido Cliente";
    });
});
