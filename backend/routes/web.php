<?php

use App\Http\Controllers\Paciente\CitaController;
use Illuminate\Support\Facades\Route;

// ========== PÁGINA DE INICIO (LANDING PÚBLICA) ==========
Route::get('/', function () {
    return view('landing');
})->name('landing');

// ========== RUTA PÚBLICA DE CITAS (sin login) ==========
Route::get('/citas', function () {
    return view('paciente.citas');
})->name('paciente.citas');

Route::post('/citas/guardar', [CitaController::class, 'store'])->name('paciente.citas.store');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Rutas del paciente (requieren login)
Route::middleware(['auth'])->prefix('paciente')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Paciente\DashboardController::class, 'index'])->name('paciente.dashboard');
    Route::get('/diagnosticos', [App\Http\Controllers\Paciente\DiagnosticoController::class, 'index'])->name('paciente.diagnosticos');
    Route::get('/recetas', [App\Http\Controllers\Paciente\RecetaController::class, 'index'])->name('paciente.recetas');
    Route::get('/resultados', [App\Http\Controllers\Paciente\ResultadoController::class, 'index'])->name('paciente.resultados');
});

// Rutas del administrador
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
});

// Rutas del médico
Route::middleware(['auth'])->prefix('medico')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Medico\DashboardController::class, 'index'])->name('medico.dashboard');
});

// Chatbot
Route::post('/chatbot/responder', [App\Http\Controllers\ChatbotController::class, 'responder'])->name('chatbot.responder')->middleware('auth');


// Libro de Reclamaciones (público)
Route::get('/reclamaciones', [App\Http\Controllers\Paciente\ReclamacionController::class, 'index'])->name('reclamaciones.index');
Route::post('/reclamaciones', [App\Http\Controllers\Paciente\ReclamacionController::class, 'store'])->name('reclamaciones.store');