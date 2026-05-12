<?php

namespace Database\Seeders;

use App\Models\Medico;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * Usuarios de prueba para entrar al panel admin y al panel médico.
 *
 * Tras ejecutar: php artisan db:seed --class=Database\\Seeders\\DemoUsersSeeder
 *
 * | Rol        | Email                 | Contraseña |
 * |------------|----------------------|------------|
 * | Admin      | admin@hospital.demo  | password   |
 * | Médico     | medico@hospital.demo | password   |
 */
class DemoUsersSeeder extends Seeder
{
    public const ADMIN_EMAIL = 'admin@hospital.demo';

    public const MEDICO_EMAIL = 'medico@hospital.demo';

    public const DEMO_PASSWORD = 'password';

    public function run(): void
    {
        if (! Schema::hasTable('usuarios') || ! Schema::hasTable('roles')) {
            $this->command?->warn('Tablas usuarios/roles no existen. Ejecuta las migraciones primero.');

            return;
        }

        $hash = Hash::make(self::DEMO_PASSWORD);

        $userAttrs = function (array $base): array {
            if (Schema::hasColumn('usuarios', 'paciente_id')) {
                $base['paciente_id'] = null;
            }

            return $base;
        };

        $especialidadId = null;
        if (Schema::hasTable('especialidades')) {
            $especialidadId = DB::table('especialidades')->orderBy('id')->value('id');
            if ($especialidadId === null) {
                $especialidadId = DB::table('especialidades')->insertGetId(['nombre' => 'Medicina general']);
            }
        }

        User::query()->updateOrCreate(
            ['email' => self::ADMIN_EMAIL],
            $userAttrs([
                'nombre' => 'Administrador demo',
                'contrasena' => $hash,
                'rol_id' => Rol::ADMIN,
                'estado' => 'activo',
            ])
        );

        $medicoUser = User::query()->updateOrCreate(
            ['email' => self::MEDICO_EMAIL],
            $userAttrs([
                'nombre' => 'Médico demo',
                'contrasena' => $hash,
                'rol_id' => Rol::MEDICO,
                'estado' => 'activo',
            ])
        );

        if (Schema::hasTable('medicos')) {
            $medico = Medico::query()->where('usuario_id', $medicoUser->id)->first();
            if ($medico === null) {
                $row = ['usuario_id' => $medicoUser->id];
                if (Schema::hasColumn('medicos', 'especialidad_id')) {
                    $row['especialidad_id'] = $especialidadId;
                }
                if (Schema::hasColumn('medicos', 'nombre')) {
                    $row['nombre'] = 'Médico demo';
                }
                if (Schema::hasColumn('medicos', 'especialidad')) {
                    $row['especialidad'] = 'Medicina general';
                }
                if (Schema::hasColumn('medicos', 'cmp')) {
                    $row['cmp'] = '00000-DEMO';
                }
                if (Schema::hasColumn('medicos', 'email')) {
                    $row['email'] = self::MEDICO_EMAIL;
                }
                Medico::query()->create($row);
            }
        }

        $this->command?->info('Demo: '.self::ADMIN_EMAIL.' y '.self::MEDICO_EMAIL.' (contraseña: '.self::DEMO_PASSWORD.')');
    }
}
