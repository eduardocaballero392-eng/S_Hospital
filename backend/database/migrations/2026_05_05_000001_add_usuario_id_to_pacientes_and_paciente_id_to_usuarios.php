<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pacientes') && !Schema::hasColumn('pacientes', 'usuario_id')) {
            Schema::table('pacientes', function (Blueprint $table) {
                $table->unsignedBigInteger('usuario_id')->nullable()->after('id');
            });
        }

        if (Schema::hasTable('usuarios') && !Schema::hasColumn('usuarios', 'paciente_id')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->unsignedBigInteger('paciente_id')->nullable()->after('id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pacientes') && Schema::hasColumn('pacientes', 'usuario_id')) {
            Schema::table('pacientes', function (Blueprint $table) {
                $table->dropColumn('usuario_id');
            });
        }

        if (Schema::hasTable('usuarios') && Schema::hasColumn('usuarios', 'paciente_id')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->dropColumn('paciente_id');
            });
        }
    }
};