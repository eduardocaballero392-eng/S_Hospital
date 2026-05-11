<?php

use App\Http\Controllers\Paciente\CitaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// ========== REDIRECCIÓN PERSONALIZADA DESPUÉS DE LOGOUT ==========
Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('landing');
})->name('logout');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// ============================================================
// RUTAS DEL PACIENTE (requieren login)
// ============================================================
Route::middleware(['auth'])->prefix('paciente')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Paciente\DashboardController::class, 'index'])->name('paciente.dashboard');
    Route::get('/diagnosticos', [App\Http\Controllers\Paciente\DiagnosticoController::class, 'index'])->name('paciente.diagnosticos');
    Route::get('/recetas', [App\Http\Controllers\Paciente\RecetaController::class, 'index'])->name('paciente.recetas');
    Route::get('/resultados', [App\Http\Controllers\Paciente\ResultadoController::class, 'index'])->name('paciente.resultados');
});

// ============================================================
// RUTAS DEL ADMINISTRADOR
// ============================================================
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
});

// ============================================================
// CHATBOT
// ============================================================
Route::post('/chatbot/responder', [App\Http\Controllers\ChatbotController::class, 'responder'])->name('chatbot.responder')->middleware('auth');

// ============================================================
// LIBRO DE RECLAMACIONES (público)
// ============================================================
Route::get('/reclamaciones', [App\Http\Controllers\Paciente\ReclamacionController::class, 'index'])->name('reclamaciones.index');
Route::post('/reclamaciones', [App\Http\Controllers\Paciente\ReclamacionController::class, 'store'])->name('reclamaciones.store');

// ============================================================
// RUTAS DEL MÉDICO
// ============================================================
Route::middleware(['auth'])->prefix('medico')->group(function () {
    
    // Dashboard principal del médico
    Route::get('/dashboard', [App\Http\Controllers\Medico\DashboardController::class, 'index'])->name('medico.dashboard');
    
    // ========== RUTAS PARA GESTIÓN DE CITAS ==========
    Route::get('/citas', [App\Http\Controllers\Medico\CitaController::class, 'index'])->name('medico.citas');
    Route::get('/citas/{id}/detalle', [App\Http\Controllers\Medico\CitaController::class, 'detalle'])->name('medico.citas.detalle');
    Route::put('/citas/{id}/confirmar', [App\Http\Controllers\Medico\CitaController::class, 'confirmar'])->name('medico.citas.confirmar');
    Route::put('/citas/{id}/cancelar', [App\Http\Controllers\Medico\CitaController::class, 'cancelar'])->name('medico.citas.cancelar');
    Route::put('/citas/{id}/completar', [App\Http\Controllers\Medico\CitaController::class, 'completar'])->name('medico.citas.completar');
    
    // ========== RUTAS PARA AGENDAR CITA DESDE PERFIL DEL PACIENTE ==========
    Route::get('/citas/agendar', [App\Http\Controllers\Medico\CitaController::class, 'agendarForm'])->name('medico.citas.agendar');
    Route::post('/citas/agendar', [App\Http\Controllers\Medico\CitaController::class, 'agendarStore'])->name('medico.citas.agendar.store');
    
    // ========== RUTAS PARA GESTIÓN DE PACIENTES ==========
    Route::get('/pacientes', [App\Http\Controllers\Medico\PacienteController::class, 'index'])->name('medico.pacientes');
    Route::get('/pacientes/{id}/detalle', [App\Http\Controllers\Medico\PacienteController::class, 'detalle'])->name('medico.pacientes.detalle');
    Route::get('/pacientes/{id}/historial', [App\Http\Controllers\Medico\PacienteController::class, 'historial'])->name('medico.pacientes.historial');
 
    
    // ========== RUTAS PARA GESTIÓN DE DIAGNÓSTICOS ==========
    Route::get('/diagnosticos', [App\Http\Controllers\Medico\DiagnosticoController::class, 'index'])->name('medico.diagnosticos');
    Route::get('/diagnosticos/crear', [App\Http\Controllers\Medico\DiagnosticoController::class, 'crear'])->name('medico.diagnosticos.crear');
    Route::post('/diagnosticos/store', [App\Http\Controllers\Medico\DiagnosticoController::class, 'store'])->name('medico.diagnosticos.store');
    
    // ========== RUTAS PARA HISTORIAL CLÍNICO ==========
    Route::get('/historial', [App\Http\Controllers\Medico\HistorialController::class, 'index'])->name('medico.historial');
    Route::get('/historial/paciente/{id}', [App\Http\Controllers\Medico\HistorialController::class, 'verPaciente'])->name('medico.historial.paciente');
    
    // ========== RUTAS PARA PERFIL DEL MÉDICO ==========
    Route::get('/perfil', [App\Http\Controllers\Medico\PerfilController::class, 'index'])->name('medico.perfil');
    Route::put('/perfil', [App\Http\Controllers\Medico\PerfilController::class, 'update'])->name('medico.perfil.update');
});


// Rutas para diagnósticos
Route::get('/diagnosticos', [App\Http\Controllers\Medico\DiagnosticoController::class, 'index'])->name('medico.diagnosticos');
Route::get('/diagnosticos/crear', [App\Http\Controllers\Medico\DiagnosticoController::class, 'crear'])->name('medico.diagnosticos.crear');
Route::post('/diagnosticos/store', [App\Http\Controllers\Medico\DiagnosticoController::class, 'store'])->name('medico.diagnosticos.store');
Route::get('/diagnosticos/{id}', [App\Http\Controllers\Medico\DiagnosticoController::class, 'show'])->name('medico.diagnosticos.show');
Route::get('/diagnosticos/{id}/editar', [App\Http\Controllers\Medico\DiagnosticoController::class, 'edit'])->name('medico.diagnosticos.edit');
Route::put('/diagnosticos/{id}', [App\Http\Controllers\Medico\DiagnosticoController::class, 'update'])->name('medico.diagnosticos.update');
Route::delete('/diagnosticos/{id}', [App\Http\Controllers\Medico\DiagnosticoController::class, 'destroy'])->name('medico.diagnosticos.destroy');