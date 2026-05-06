<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Paciente\CitaController;
use App\Http\Controllers\Paciente\DiagnosticoController;
use App\Http\Controllers\Paciente\RecetaController;
use App\Http\Controllers\Paciente\ResultadoController;
use App\Http\Controllers\Paciente\ReclamacionController;
use App\Http\Controllers\ChatbotController;

// ── Públicas (sin login) ──
Route::post('/citas', [CitaController::class, 'store']);
Route::get('/citas', [CitaController::class, 'index']);
Route::post('/reclamaciones', [ReclamacionController::class, 'store']);

// ── Privadas (requieren login) ──
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/paciente/dashboard', [App\Http\Controllers\Paciente\DashboardController::class, 'index']);
    Route::get('/paciente/diagnosticos', [DiagnosticoController::class, 'index']);
    Route::get('/paciente/recetas', [RecetaController::class, 'index']);
    Route::get('/paciente/resultados', [ResultadoController::class, 'index']);
    Route::post('/chatbot/responder', [ChatbotController::class, 'responder']);
});