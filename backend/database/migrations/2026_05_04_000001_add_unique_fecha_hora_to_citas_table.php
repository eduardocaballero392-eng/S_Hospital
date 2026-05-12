<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('citas')) {
            return;
        }

        $tableName = Schema::getConnection()->getTablePrefix().'citas';
        if ($this->indexExists($tableName, 'citas_fecha_hora_unique')) {
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

    /**
     * Comprueba si existe un índice sin usar sintaxis específica de MySQL (p. ej. PostgreSQL en Render).
     */
    private function indexExists(string $tableName, string $indexName): bool
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            $quoted = '`'.str_replace('`', '``', $tableName).'`';

            return collect(DB::select(
                "SHOW INDEX FROM {$quoted} WHERE Key_name = ?",
                [$indexName]
            ))->isNotEmpty();
        }

        if ($driver === 'pgsql') {
            // Los identificadores sin comillas se guardan en minúsculas en PostgreSQL.
            $row = DB::selectOne(
                'SELECT 1 AS one FROM pg_indexes WHERE schemaname = ANY (current_schemas(false)) AND tablename = ? AND indexname = ?',
                [mb_strtolower($tableName), mb_strtolower($indexName)]
            );

            return $row !== null;
        }

        if ($driver === 'sqlite') {
            return collect(DB::select(
                "SELECT name FROM sqlite_master WHERE type = 'index' AND tbl_name = ? AND name = ?",
                [$tableName, $indexName]
            ))->isNotEmpty();
        }

        return false;
    }
};

