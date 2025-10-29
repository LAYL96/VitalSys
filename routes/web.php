<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController;

use App\Http\Controllers\Cliente\ClientAppointmentController;

use App\Http\Controllers\Medico\ConsultationController;
use App\Http\Controllers\Medico\MedicoDashboardController;
use App\Http\Controllers\Medico\PatientController as MedicoPatientController;
use App\Http\Controllers\Medico\DoctorAppointmentController;

use App\Http\Controllers\ProductController as PublicProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['web'])->group(function () {

    /* ============================================================
    |  PÚBLICO (sin autenticación)
    ============================================================ */
    Route::get('/', [PublicController::class, 'index'])->name('home');

    /* ============================================================
    |  DASHBOARD GENERAL (autenticado + verificado)
    ============================================================ */
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    /* ============================================================
    |  PERFIL USUARIO
    ============================================================ */
    Route::middleware('auth')->group(function () {
        Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    /* ============================================================
    |  AUTH
    ============================================================ */
    require __DIR__ . '/auth.php';

    /* ============================================================
    |  ADMINISTRADOR
    ============================================================ */
    Route::middleware(['auth', 'role:Administrador'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

            Route::resource('users',      UserController::class);
            Route::resource('categories', CategoryController::class);
            Route::resource('suppliers',  SupplierController::class);
            Route::resource('products',   AdminProductController::class);

            Route::get('/reports/inventory/pdf',   [ReportController::class, 'inventoryPdf'])->name('reports.inventory.pdf');
            Route::get('/reports/inventory/excel', [ReportController::class, 'exportInventoryExcel'])->name('reports.inventory.excel');
        });

    /* ============================================================
    |  EMPLEADO
    ============================================================ */
    Route::middleware(['auth', 'role:Empleado'])->group(function () {
        Route::get('/empleado', function () {
            return "Bienvenido Empleado";
        })->name('empleado.dashboard');
    });

    /* ============================================================
    |  MÉDICO
    |  *** Incluye completar consultas ***
    ============================================================ */
    Route::middleware(['auth', 'role:Médico|Medico'])
        ->prefix('medico')
        ->name('medico.')
        ->group(function () {

            // Dashboard Médico (pendientes, completadas, canceladas)
            Route::get('/dashboard', [MedicoDashboardController::class, 'index'])->name('dashboard');

            // Cambiar estado (solo cancelar desde dashboard; completar redirige al formulario)
            Route::patch('/appointments/{id}/status', [MedicoDashboardController::class, 'updateStatus'])
                ->name('appointments.updateStatus');

            // Pacientes
            Route::resource('patients', MedicoPatientController::class);

            // Citas del médico (opcional si usas solo el dashboard)
            Route::get('/citas', [DoctorAppointmentController::class, 'index'])
                ->name('appointments.index');

            // Flujo de completar consulta (form + guardar)
            Route::get('/citas/{appointment}/completar', [DoctorAppointmentController::class, 'complete'])
                ->name('appointments.complete');

            Route::post('/citas/{appointment}/completar', [DoctorAppointmentController::class, 'store'])
                ->name('appointments.store');
        });

    /* ============================================================
    |  CLIENTE
    ============================================================ */
    Route::middleware(['auth', 'role:Cliente'])
        ->prefix('cliente')
        ->name('cliente.')
        ->group(function () {

            Route::get('/', function () {
                return view('cliente.dashboard');
            })->name('dashboard');

            // Citas del cliente
            Route::get('/citas',       [ClientAppointmentController::class, 'index'])->name('appointments.index');
            Route::get('/citas/nueva', [ClientAppointmentController::class, 'create'])->name('appointments.create');
            Route::post('/citas',      [ClientAppointmentController::class, 'store'])->name('appointments.store');

            // AJAX — Horarios disponibles (mantenemos el mismo name que usa tu JS)
            Route::get('/citas/horarios', [ClientAppointmentController::class, 'getAvailableSlots'])
                ->name('appointments.slots');
        });

    /* ============================================================
    |  PRODUCTOS PÚBLICOS
    ============================================================ */
    Route::get('/productos',           [PublicProductController::class, 'publicIndex'])->name('products.public');
    Route::get('/productos/{product}', [PublicProductController::class, 'show'])->name('products.show');
});
