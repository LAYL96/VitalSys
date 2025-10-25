<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Cliente\ClientAppointmentController;
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
        Route::patch('/appointments/{id}/status', [MedicoDashboardController::class, 'updateStatus'])
            ->name('appointments.updateStatus');

        // CRUD de pacientes
        Route::resource('patients', \App\Http\Controllers\Medico\PatientController::class);
    });

// ===========================================
// Panel del Cliente
// ===========================================
// Acceso restringido al rol "Cliente"
Route::middleware(['auth', 'role:Cliente'])
    ->prefix('cliente')
    ->name('cliente.')
    ->group(function () {

        // Dashboard básico del cliente
        Route::get('/', function () {
            return view('cliente.dashboard');
        })->name('dashboard');

        // ===========================================
        // Gestión de Citas Médicas del Cliente
        // ===========================================

        // Listar citas del cliente autenticado
        Route::get('/citas', [ClientAppointmentController::class, 'index'])
            ->name('appointments.index');

        // Formulario para crear una nueva cita
        Route::get('/citas/nueva', [ClientAppointmentController::class, 'create'])
            ->name('appointments.create');

        // Guardar la nueva cita
        Route::post('/citas', [ClientAppointmentController::class, 'store'])
            ->name('appointments.store');
    });
// ===========================================
// Productos públicos (sin autenticación)
// ===========================================
Route::get('/productos', [PublicProductController::class, 'publicIndex'])->name('products.public');
Route::get('/productos/{product}', [PublicProductController::class, 'show'])->name('products.show');
