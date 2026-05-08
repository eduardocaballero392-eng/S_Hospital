<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('roles')) {
            return;
        }

        $exists = DB::table('roles')->where('id', 3)->exists();
        if (!$exists) {
            DB::table('roles')->insert([
                'id' => 3,
                'nombre' => 'Médico',
            ]);
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('roles')) {
            return;
        }

        DB::table('roles')->where('id', 3)->delete();
    }
};
