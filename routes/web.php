<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

// ===========================================
// Página de bienvenida / landing page
// ===========================================
Route::get('/', [PublicController::class, 'index'])->name('home');

// ===========================================
// Dashboard (requiere autenticación y email verificado)
// ===========================================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ===========================================
// Rutas de perfil (solo usuarios autenticados)
// ===========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===========================================
// Autenticación (Breeze / Jetstream)
// ===========================================
require __DIR__ . '/auth.php';

// ===========================================
// Rutas de administración (solo Administrador)
// ===========================================
Route::middleware(['auth', RoleMiddleware::class . ':Administrador'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::resource('users', UserController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('products', ProductController::class);
    });

// ===========================================
// Rutas específicas para otros roles
// ===========================================
// Empleado
Route::middleware(['auth', RoleMiddleware::class . ':Empleado'])->group(function () {
    Route::get('/empleado', function () {
        return "Bienvenido Empleado";
    })->name('empleado.dashboard');
});

// Médico
Route::middleware(['auth', RoleMiddleware::class . ':Médico'])->group(function () {
    Route::get('/medico', function () {
        return "Bienvenido Médico";
    })->name('medico.dashboard');
});

// Cliente
Route::middleware(['auth', RoleMiddleware::class . ':Cliente'])->group(function () {
    Route::get('/cliente', function () {
        return "Bienvenido Cliente";
    })->name('cliente.dashboard');
});

// ===========================================
// Productos públicos
// ===========================================
Route::get('/productos', [ProductController::class, 'publicIndex'])->name('products.public');
Route::get('/productos/{product}', [ProductController::class, 'show'])->name('products.show');
