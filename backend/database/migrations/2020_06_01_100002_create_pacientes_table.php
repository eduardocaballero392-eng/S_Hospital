<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->string('DNI', 15)->unique();
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->date('fecha_nac');
            $table->string('genero', 30);
            $table->string('telefono', 15)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('direccion', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
