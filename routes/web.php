<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Medico\MedicoDashboardController;
use App\Http\Controllers\ProductController as PublicProductController;
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

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', UserController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('products', AdminProductController::class);

        Route::get('/reports/inventory/pdf', [ReportController::class, 'inventoryPdf'])
            ->name('reports.inventory.pdf');

        Route::get('/reports/inventory/excel', [ReportController::class, 'exportInventoryExcel'])
            ->name('reports.inventory.excel');
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
Route::middleware(['auth', RoleMiddleware::class . ':Medico'])
    ->prefix('medico')
    ->name('medico.')
    ->group(function () {
        Route::get('/dashboard', [MedicoDashboardController::class, 'index'])->name('dashboard');
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
Route::get('/productos', [PublicProductController::class, 'publicIndex'])->name('products.public');
Route::get('/productos/{product}', [PublicProductController::class, 'show'])->name('products.show');
