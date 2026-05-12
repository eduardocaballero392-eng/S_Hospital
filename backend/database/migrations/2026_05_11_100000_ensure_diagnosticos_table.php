<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('diagnosticos')) {
            Schema::create('diagnosticos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
                $table->foreignId('medico_id')->nullable()->constrained('medicos')->nullOnDelete();
                $table->foreignId('cita_id')->nullable()->constrained('citas')->nullOnDelete();
                $table->string('nombre', 255);
                $table->text('descripcion')->nullable();
                $table->string('tipo', 50)->default('preventivo');
                $table->date('fecha_diagnostico');
                $table->timestamp('created_at')->nullable();
            });

            return;
        }

        Schema::table('diagnosticos', function (Blueprint $table) {
            if (!Schema::hasColumn('diagnosticos', 'cita_id')) {
                $table->foreignId('cita_id')->nullable()->constrained('citas')->nullOnDelete();
            }
            if (!Schema::hasColumn('diagnosticos', 'tipo')) {
                $table->string('tipo', 50)->default('preventivo');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('diagnosticos')) {
            return;
        }

        Schema::table('diagnosticos', function (Blueprint $table) {
            if (Schema::hasColumn('diagnosticos', 'cita_id')) {
                $table->dropConstrainedForeignId('cita_id');
            }
            if (Schema::hasColumn('diagnosticos', 'tipo')) {
                $table->dropColumn('tipo');
            }
        });
    }
};
