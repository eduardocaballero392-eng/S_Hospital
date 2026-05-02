<?php

use Illuminate\Support\Facades\Route;

// ========== PÁGINA DE INICIO (LANDING PÚBLICA) ==========
Route::get('/', function () {
    return view('landing');
})->name('landing');

// ========== RUTA PÚBLICA DE CITAS (sin login) ==========
Route::get('/citas', [App\Http\Controllers\Paciente\CitaController::class, 'index'])->name('paciente.citas');
Route::post('/citas/guardar', [App\Http\Controllers\Paciente\CitaController::class, 'store'])->name('paciente.citas.store'); // ← store sigue con auth

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

// Chatbot
Route::post('/chatbot/responder', [App\Http\Controllers\ChatbotController::class, 'responder'])->name('chatbot.responder')->middleware('auth');