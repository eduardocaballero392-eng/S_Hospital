<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('citas')) {
            return;
        }

        Schema::table('citas', function (Blueprint $table) {
            // Nombre explícito para evitar duplicados de nombres en distintos motores.
            $table->unique('fecha_hora', 'citas_fecha_hora_unique');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('citas')) {
            return;
        }

        Schema::table('citas', function (Blueprint $table) {
            $table->dropUnique('citas_fecha_hora_unique');
        });
    }
};

