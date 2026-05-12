<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('usuarios') || ! Schema::hasTable('pacientes')) {
            return;
        }
        if (! Schema::hasColumn('usuarios', 'paciente_id') || ! Schema::hasColumn('pacientes', 'usuario_id')) {
            return;
        }

        $rows = DB::table('pacientes')
            ->whereNotNull('usuario_id')
            ->select(['id', 'usuario_id'])
            ->get();

        foreach ($rows as $row) {
            DB::table('usuarios')
                ->where('id', $row->usuario_id)
                ->where(function ($q) {
                    $q->whereNull('paciente_id')->orWhere('paciente_id', 0);
                })
                ->update(['paciente_id' => $row->id]);
        }
    }

    public function down(): void
    {
        // No revert: datos de enlace válidos.
    }
};
