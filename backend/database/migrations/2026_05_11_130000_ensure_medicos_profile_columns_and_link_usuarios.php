<?php

use App\Models\Rol;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('medicos')) {
            Schema::table('medicos', function (Blueprint $table) {
                if (! Schema::hasColumn('medicos', 'nombre')) {
                    $table->string('nombre', 255)->nullable()->after('usuario_id');
                }
                if (! Schema::hasColumn('medicos', 'especialidad')) {
                    $table->string('especialidad', 255)->nullable()->after('nombre');
                }
                if (! Schema::hasColumn('medicos', 'telefono')) {
                    $table->string('telefono', 50)->nullable()->after('especialidad');
                }
                if (! Schema::hasColumn('medicos', 'email')) {
                    $table->string('email', 255)->nullable()->after('telefono');
                }
            });
        }

        if (Schema::hasTable('medicos') && Schema::hasTable('usuarios')) {
            $medicos = DB::table('medicos')
                ->whereNull('usuario_id')
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->get(['id', 'email']);

            foreach ($medicos as $m) {
                $uid = DB::table('usuarios')
                    ->where('email', $m->email)
                    ->where('rol_id', Rol::MEDICO)
                    ->value('id');
                if ($uid) {
                    DB::table('medicos')->where('id', $m->id)->update(['usuario_id' => $uid]);
                }
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('medicos')) {
            return;
        }

        Schema::table('medicos', function (Blueprint $table) {
            foreach (['email', 'telefono', 'especialidad', 'nombre'] as $col) {
                if (Schema::hasColumn('medicos', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
