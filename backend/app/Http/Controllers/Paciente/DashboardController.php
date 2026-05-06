<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $usuario = Auth::user();

        // ──────────────────────────────────────────────────────────────
        // El usuario tiene paciente_id que apunta a la tabla 'pacientes'
        // Las citas usan paciente_id de la tabla 'pacientes', NO de usuarios
        // ──────────────────────────────────────────────────────────────
        $pacienteId = $usuario->paciente_id;

        $citasPendientes = 0;
        $proximaCita = null;

        if (Schema::hasTable('citas') && $pacienteId) {
            // 1. Cantidad de citas del paciente en el mes actual
            $citasPendientes = DB::table('citas')
                ->where('paciente_id', $pacienteId)
                ->whereMonth('fecha_hora', now()->month)
                ->whereYear('fecha_hora',  now()->year)
                ->count();

            // 2. Próxima cita (joins opcionales según tablas existentes)
            $query = DB::table('citas')
                ->where('citas.paciente_id', $pacienteId)
                ->where('citas.fecha_hora',  '>=', now());

            if (Schema::hasTable('medicos')) {
                $query->leftJoin('medicos', 'citas.medico_id', '=', 'medicos.id');
            }
            if (Schema::hasTable('usuarios') && Schema::hasTable('medicos')) {
                $query->leftJoin('usuarios', 'medicos.usuario_id', '=', 'usuarios.id');
            }
            if (Schema::hasTable('especialidades') && Schema::hasTable('medicos')) {
                $query->leftJoin('especialidades', 'medicos.especialidad_id', '=', 'especialidades.id');
            }

            $selects = [
                'citas.id',
                'citas.fecha_hora',
                'citas.motivo',
                'citas.tipo',
                'citas.estado',
            ];

            if (Schema::hasTable('usuarios') && Schema::hasTable('medicos')) {
                $selects[] = DB::raw("IFNULL(usuarios.nombre, 'Por asignar') as medico_nombre");
            } else {
                $selects[] = DB::raw("'Por asignar' as medico_nombre");
            }

            if (Schema::hasTable('especialidades') && Schema::hasTable('medicos')) {
                $selects[] = DB::raw("IFNULL(especialidades.nombre, 'Sin asignar') as especialidad");
            } else {
                $selects[] = DB::raw("'Sin asignar' as especialidad");
            }

            $proximaCita = $query
                ->select($selects)
                ->orderBy('citas.fecha_hora', 'asc')
                ->first();
        }

        return view('paciente.dashboard', compact(
            'usuario',
            'citasPendientes',
            'proximaCita'
        ));
    }
}