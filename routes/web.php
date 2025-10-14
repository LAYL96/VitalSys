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
use Illuminate\Support\Facades\Route;

// ===========================================
// Página de inicio pública (landing page)
// ===========================================
Route::get('/', [PublicController::class, 'index'])->name('home');

// ===========================================
// Dashboard general (solo autenticados y verificados)
// ===========================================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ===========================================
// Gestión de perfil (solo usuarios autenticados)
// ===========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===========================================
// Autenticación predeterminada (Laravel Breeze / Jetstream)
// ===========================================
require __DIR__ . '/auth.php';

// ===========================================
// Panel de Administración (solo rol Administrador)
// ===========================================
// Uso del middleware 'role' provisto por Spatie Laravel Permission
Route::middleware(['auth', 'role:Administrador'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard del administrador con alertas de stock
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Rutas CRUD de gestión interna
        Route::resource('users', UserController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('products', AdminProductController::class);

        // Reportes de inventario
        Route::get('/reports/inventory/pdf', [ReportController::class, 'inventoryPdf'])
            ->name('reports.inventory.pdf');

        Route::get('/reports/inventory/excel', [ReportController::class, 'exportInventoryExcel'])
            ->name('reports.inventory.excel');
    });

// ===========================================
// Panel del Empleado
// ===========================================
// Acceso restringido al rol "Empleado"
Route::middleware(['auth', 'role:Empleado'])->group(function () {
    Route::get('/empleado', function () {
        return "Bienvenido Empleado";
    })->name('empleado.dashboard');
});

// ===========================================
// Panel del Médico
// ===========================================
// Acceso restringido al rol "Médico"
Route::middleware(['auth', 'role:Médico'])
    ->prefix('medico')
    ->name('medico.')
    ->group(function () {
        Route::get('/dashboard', [MedicoDashboardController::class, 'index'])
            ->name('dashboard');

        // Actualizar estado de la cita (completar o cancelar)
        Route::patch('/appointments/{appointment}/status', [MedicoDashboardController::class, 'updateStatus'])
            ->name('appointments.updateStatus');
    });

// ===========================================
// Panel del Cliente
// ===========================================
// Acceso restringido al rol "Cliente"
Route::middleware(['auth', 'role:Cliente'])->group(function () {
    Route::get('/cliente', function () {
        return "Bienvenido Cliente";
    })->name('cliente.dashboard');
});

// ===========================================
// Productos públicos (sin autenticación)
// ===========================================
Route::get('/productos', [PublicProductController::class, 'publicIndex'])->name('products.public');
Route::get('/productos/{product}', [PublicProductController::class, 'show'])->name('products.show');
