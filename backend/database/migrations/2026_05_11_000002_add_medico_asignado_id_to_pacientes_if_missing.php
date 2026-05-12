<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pacientes') || Schema::hasColumn('pacientes', 'medico_asignado_id')) {
            return;
        }

        Schema::table('pacientes', function (Blueprint $table) {
            $table->foreignId('medico_asignado_id')->nullable()->after('direccion')->constrained('medicos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('pacientes') || !Schema::hasColumn('pacientes', 'medico_asignado_id')) {
            return;
        }

        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('medico_asignado_id');
        });
    }
};
