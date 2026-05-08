<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('medico_id')->nullable()->constrained('medicos')->nullOnDelete();
            $table->foreignId('sala_id')->nullable()->constrained('salas')->nullOnDelete();
            $table->dateTime('fecha_hora');
            $table->string('estado', 50)->default('pendiente');
            $table->string('motivo', 255)->nullable();
            $table->string('tipo', 100)->nullable();
            $table->unique('fecha_hora', 'citas_fecha_hora_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
