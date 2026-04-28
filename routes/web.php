<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Rutas del paciente
Route::middleware(['auth'])->prefix('paciente')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Paciente\DashboardController::class, 'index'])->name('paciente.dashboard');
});

// Rutas del administrador
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
});

// chat bot
Route::post('/chatbot/responder', [App\Http\Controllers\ChatbotController::class, 'responder'])->name('chatbot.responder')->middleware('auth');
