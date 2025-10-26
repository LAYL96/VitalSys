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

use App\Http\Controllers\ProductController as PublicProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| WEB ROUTES (con middleware 'web' explícito)
| Esto garantiza el manejo correcto de sesiones/cookies para @auth/@guest.
|--------------------------------------------------------------------------
*/

Route::middleware(['web'])->group(function () {

    /* ============================================================
    |  PÚBLICO (sin autenticación)
    |============================================================ */
    Route::get('/', [PublicController::class, 'index'])->name('home');

    /* ============================================================
    |  DASHBOARD GENERAL (autenticado + verificado)
    |  Nota: dashboard genérico; cada rol tiene su panel propio
    |============================================================ */
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    /* ============================================================
    |  PERFIL (autenticado)
    |============================================================ */
    Route::middleware('auth')->group(function () {
        Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    /* ============================================================
    |  AUTH (Breeze / Jetstream)
    |  IMPORTANTE: estas rutas deben estar bajo 'web'
    |============================================================ */
    require __DIR__ . '/auth.php';

    /* ============================================================
    |  ADMINISTRACIÓN (auth + role:Administrador)
    |============================================================ */
    Route::middleware(['auth', 'role:Administrador'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            // Dashboard del administrador con alertas de stock
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

            // CRUDs
            Route::resource('users',      UserController::class);
            Route::resource('categories', CategoryController::class);
            Route::resource('suppliers',  SupplierController::class);
            Route::resource('products',   AdminProductController::class);

            // Reportes de inventario
            Route::get('/reports/inventory/pdf',   [ReportController::class, 'inventoryPdf'])->name('reports.inventory.pdf');
            Route::get('/reports/inventory/excel', [ReportController::class, 'exportInventoryExcel'])->name('reports.inventory.excel');
        });

    /* ============================================================
    |  EMPLEADO (auth + role:Empleado)
    |============================================================ */
    Route::middleware(['auth', 'role:Empleado'])->group(function () {
        Route::get('/empleado', function () {
            return "Bienvenido Empleado";
        })->name('empleado.dashboard');
    });

    /* ============================================================
    |  MÉDICO (auth + role:Médico)
    |  - Dashboard de médico
    |  - Gestión de Pacientes
    |  - Registro de Consultas médicas (diagnóstico/receta)
    |============================================================ */
    Route::middleware(['auth', 'role:Médico'])
        ->prefix('medico')
        ->name('medico.')
        ->group(function () {

            // Dashboard del médico (citas por estado)
            Route::get('/dashboard', [MedicoDashboardController::class, 'index'])->name('dashboard');

            // Actualizar estado de cita (pendiente/completada/cancelada)
            Route::patch('/appointments/{id}/status', [MedicoDashboardController::class, 'updateStatus'])
                ->name('appointments.updateStatus');

            // CRUD de Pacientes del médico
            Route::resource('patients', MedicoPatientController::class);

            // CONSULTAS MÉDICAS
            Route::prefix('consultas')->name('consultations.')->group(function () {
                // Crear consulta a partir de una cita
                Route::get('/crear/{appointment}',  [ConsultationController::class, 'create'])->name('create');
                Route::post('/crear/{appointment}', [ConsultationController::class, 'store'])->name('store');

                // Editar / Ver consulta existente
                Route::get('/{consultation}/editar', [ConsultationController::class, 'edit'])->name('edit');
                Route::put('/{consultation}',        [ConsultationController::class, 'update'])->name('update');
                Route::get('/{consultation}',        [ConsultationController::class, 'show'])->name('show');
            });
        });

    /* ============================================================
    |  CLIENTE (auth + role:Cliente)
    |  - Dashboard cliente
    |  - Citas del cliente (index/create/store)
    |============================================================ */
    Route::middleware(['auth', 'role:Cliente'])
        ->prefix('cliente')
        ->name('cliente.')
        ->group(function () {

            // Dashboard básico del cliente
            Route::get('/', function () {
                return view('cliente.dashboard');
            })->name('dashboard');

            // Citas del cliente
            Route::get('/citas',       [ClientAppointmentController::class, 'index'])->name('appointments.index');
            Route::get('/citas/nueva', [ClientAppointmentController::class, 'create'])->name('appointments.create');
            Route::post('/citas',      [ClientAppointmentController::class, 'store'])->name('appointments.store');
        });

    /* ============================================================
    |  ENDPOINT AJAX — Horarios disponibles (cliente)
    |  Mantiene el name que usa el JS: cliente.appointments.slots
    |============================================================ */
    Route::get('/citas/horarios', [ClientAppointmentController::class, 'getAvailableSlots'])
        ->name('cliente.appointments.slots')
        ->middleware(['auth', 'role:Cliente']);

    /* ============================================================
    |  PRODUCTOS PÚBLICOS (sin autenticación)
    |============================================================ */
    Route::get('/productos',           [PublicProductController::class, 'publicIndex'])->name('products.public');
    Route::get('/productos/{product}', [PublicProductController::class, 'show'])->name('products.show');
});
