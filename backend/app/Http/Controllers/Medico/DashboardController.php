<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Diagnostico;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Support\Facades\Auth;
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
        $medico = Medico::where('usuario_id', $usuario->id)->first();

        $citasHoyList = collect();
        $citasPendientesList = collect();
        $citasHoyCount = 0;
        $citasPendientesCount = 0;
        $totalPacientes = 0;
        $totalDiagnosticos = 0;

        if ($medico) {
            $base = Cita::query()->paraMedico($medico->id)->with('paciente');

            $citasHoyList = (clone $base)
                ->whereDate('fecha_hora', today())
                ->whereRaw('LOWER(TRIM(estado)) NOT IN (?, ?)', ['cancelada', 'cancelado'])
                ->orderBy('fecha_hora')
                ->get();

            $citasHoyCount = $citasHoyList->count();

            $estadosPendienteOProgramada = [
                'pendiente', 'pendientes', 'programada', 'programado',
                'agendada', 'agendado', 'confirmada', 'confirmado',
            ];
            $ph = implode(',', array_fill(0, count($estadosPendienteOProgramada), '?'));

            $citasPendientesList = (clone $base)
                ->whereRaw('LOWER(TRIM(estado)) IN (' . $ph . ')', $estadosPendienteOProgramada)
                ->orderBy('fecha_hora')
                ->take(12)
                ->get();

            $citasPendientesCount = Cita::query()
                ->paraMedico($medico->id)
                ->whereRaw('LOWER(TRIM(estado)) IN (' . $ph . ')', $estadosPendienteOProgramada)
                ->count();

            $idsCitas = Cita::query()->paraMedico($medico->id)->pluck('paciente_id');
            $idsAsignados = collect();
            if (Schema::hasColumn('pacientes', 'medico_asignado_id')) {
                $idsAsignados = Paciente::query()->where('medico_asignado_id', $medico->id)->pluck('id');
            }
            $totalPacientes = $idsCitas->merge($idsAsignados)->unique()->filter()->count();

            if (Schema::hasTable('diagnosticos')) {
                $totalDiagnosticos = Diagnostico::query()
                    ->where('medico_id', $medico->id)
                    ->whereMonth('fecha_diagnostico', now()->month)
                    ->whereYear('fecha_diagnostico', now()->year)
                    ->count();
            }
        }

        return view('medico.dashboard', compact(
            'usuario',
            'medico',
            'citasHoyList',
            'citasHoyCount',
            'citasPendientesList',
            'citasPendientesCount',
            'totalPacientes',
            'totalDiagnosticos'
        ));
    }
}
