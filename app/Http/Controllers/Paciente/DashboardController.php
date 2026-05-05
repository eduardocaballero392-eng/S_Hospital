<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // 1. Cantidad de citas del paciente en el mes actual
        $citasPendientes = DB::table('citas')
            ->where('paciente_id', $pacienteId)
            ->whereMonth('fecha_hora', now()->month)
            ->whereYear('fecha_hora',  now()->year)
            ->count();

        // 2. Próxima cita con nombre del médico y especialidad
        $proximaCita = DB::table('citas')
            ->leftJoin('medicos',        'citas.medico_id',         '=', 'medicos.id')
            ->leftJoin('usuarios',       'medicos.usuario_id',      '=', 'usuarios.id')
            ->leftJoin('especialidades', 'medicos.especialidad_id', '=', 'especialidades.id')
            ->where('citas.paciente_id', $pacienteId)
            ->where('citas.fecha_hora',  '>=', now())
            ->select(
                'citas.id',
                'citas.fecha_hora',
                'citas.motivo',
                'citas.tipo',
                'citas.estado',
                DB::raw("IFNULL(usuarios.nombre, 'Por asignar')       as medico_nombre"),
                DB::raw("IFNULL(especialidades.nombre, 'Sin asignar') as especialidad")
            )
            ->orderBy('citas.fecha_hora', 'asc')
            ->first();

        return view('paciente.dashboard', compact(
            'usuario',
            'citasPendientes',
            'proximaCita'
        ));
    }
}