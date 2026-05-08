<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reclamaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('tipo_documento', 50);
            $table->string('nro_documento', 15);
            $table->string('email', 150);
            $table->string('telefono', 15)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('tipo_reclamo', 100);
            $table->string('detalle', 1000);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reclamaciones');
    }
};
