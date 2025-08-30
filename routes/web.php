<?php

use App\Http\Controllers\ProfileController;
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

Route::middleware(['auth', 'role:Administrador'])->group(function () {
    Route::get('/admin', function () {
        return "Bienvenido Administrador";
    });
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
