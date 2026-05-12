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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// ============================================================
// RUTAS DEL PACIENTE (requieren login)
// ============================================================
Route::middleware(['auth'])->prefix('paciente')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Paciente\DashboardController::class, 'index'])->name('paciente.dashboard');
    Route::get('/diagnosticos', [App\Http\Controllers\Paciente\DiagnosticoController::class, 'index'])->name('paciente.diagnosticos');
    Route::get('/resultados', [App\Http\Controllers\Paciente\ResultadoController::class, 'index'])->name('paciente.resultados');
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
    
    // Rutas para gestión de citas
    Route::get('/citas', [App\Http\Controllers\Medico\CitaController::class, 'index'])->name('medico.citas');
    Route::get('/citas/{id}/detalle', [App\Http\Controllers\Medico\CitaController::class, 'detalle'])->name('medico.citas.detalle');
    Route::put('/citas/{id}/confirmar', [App\Http\Controllers\Medico\CitaController::class, 'confirmar'])->name('medico.citas.confirmar');
    Route::put('/citas/{id}/cancelar', [App\Http\Controllers\Medico\CitaController::class, 'cancelar'])->name('medico.citas.cancelar');
    Route::put('/citas/{id}/completar', [App\Http\Controllers\Medico\CitaController::class, 'completar'])->name('medico.citas.completar');
    Route::post('/citas/{id}/atender', [App\Http\Controllers\Medico\CitaController::class, 'atender'])->name('medico.citas.atender');
    Route::post('/citas/finalizar', [App\Http\Controllers\Medico\CitaController::class, 'finalizar'])->name('medico.citas.finalizar');
    
    // Rutas para agendar cita
    Route::get('/citas/agendar', [App\Http\Controllers\Medico\CitaController::class, 'agendarForm'])->name('medico.citas.agendar');
    Route::post('/citas/agendar', [App\Http\Controllers\Medico\CitaController::class, 'agendarStore'])->name('medico.citas.agendar.store');
    
    // Rutas para gestión de pacientes
    Route::get('/pacientes', [App\Http\Controllers\Medico\PacienteController::class, 'index'])->name('medico.pacientes');
    Route::get('/pacientes/{id}/detalle', [App\Http\Controllers\Medico\PacienteController::class, 'detalle'])->name('medico.pacientes.detalle');
    Route::get('/pacientes/{id}/historial', [App\Http\Controllers\Medico\PacienteController::class, 'historial'])->name('medico.pacientes.historial');
 
    // Rutas para gestión de diagnósticos
    Route::get('/diagnosticos', [App\Http\Controllers\Medico\DiagnosticoController::class, 'index'])->name('medico.diagnosticos');
    Route::get('/diagnosticos/crear', [App\Http\Controllers\Medico\DiagnosticoController::class, 'crear'])->name('medico.diagnosticos.crear');
    Route::post('/diagnosticos/store', [App\Http\Controllers\Medico\DiagnosticoController::class, 'store'])->name('medico.diagnosticos.store');
    Route::get('/diagnosticos/{id}', [App\Http\Controllers\Medico\DiagnosticoController::class, 'show'])->name('medico.diagnosticos.show');
    Route::get('/diagnosticos/{id}/editar', [App\Http\Controllers\Medico\DiagnosticoController::class, 'edit'])->name('medico.diagnosticos.edit');
    Route::put('/diagnosticos/{id}', [App\Http\Controllers\Medico\DiagnosticoController::class, 'update'])->name('medico.diagnosticos.update');
    Route::delete('/diagnosticos/{id}', [App\Http\Controllers\Medico\DiagnosticoController::class, 'destroy'])->name('medico.diagnosticos.destroy');
    
    // Rutas para historial clínico
    Route::get('/historial', [App\Http\Controllers\Medico\HistorialController::class, 'index'])->name('medico.historial');
    Route::get('/historial/paciente/{id}', [App\Http\Controllers\Medico\HistorialController::class, 'verPaciente'])->name('medico.historial.paciente');
    
    // Rutas para perfil del médico
    Route::get('/perfil', [App\Http\Controllers\Medico\PerfilController::class, 'index'])->name('medico.perfil');
    Route::put('/perfil', [App\Http\Controllers\Medico\PerfilController::class, 'update'])->name('medico.perfil.update');
});

// ============================================================
// RUTAS DEL ADMINISTRADOR
// ============================================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Recursos CRUD
    // Reclamaciones bajo Pacientes (URLs antes del resource para no confundir con {paciente})
    Route::get('pacientes/reclamaciones', [App\Http\Controllers\Admin\ReclamacionController::class, 'index'])->name('reclamaciones.index');
    Route::get('pacientes/reclamaciones/{reclamacion}', [App\Http\Controllers\Admin\ReclamacionController::class, 'show'])->name('reclamaciones.show');
    Route::delete('pacientes/reclamaciones/{reclamacion}', [App\Http\Controllers\Admin\ReclamacionController::class, 'destroy'])->name('reclamaciones.destroy');

    Route::resource('pacientes', App\Http\Controllers\Admin\PacienteController::class);
    Route::resource('medicos', App\Http\Controllers\Admin\MedicoController::class);
    Route::resource('citas', App\Http\Controllers\Admin\CitaController::class);
    Route::resource('usuarios', App\Http\Controllers\Admin\UsuarioController::class);
});