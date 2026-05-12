<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('resultados')) {
            return;
        }

        Schema::create('resultados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->string('nombre', 255);
            $table->text('descripcion')->nullable();
            $table->string('tipo', 50)->default('laboratorio');
            $table->string('especialista', 255)->nullable();
            $table->string('servicio', 255)->nullable();
            $table->dateTime('fecha_resultado');
            $table->string('estado', 50)->default('normal');
            $table->text('detalle')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultados');
    }
};
