<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Diagnostico;
use App\Models\Medico;
use App\Models\Resultado;
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
        $pacienteId = $usuario->resolvePacienteId();

        $citasPendientes = 0;
        $proximaCita = null;
        $resultados = collect();
        $resultadosPendientes = 0;
        $diagnosticosRecientes = collect();
        $totalDiagnosticos = 0;

        if ($pacienteId && Schema::hasTable('citas')) {
            $citasPendientes = Cita::query()
                ->where('paciente_id', $pacienteId)
                ->whereRaw('LOWER(TRIM(estado)) = ?', ['pendiente'])
                ->count();

            $cita = Cita::query()
                ->where('paciente_id', $pacienteId)
                ->where('fecha_hora', '>=', now())
                ->with(['medico.usuario', 'paciente.medicoAsignado.usuario'])
                ->orderBy('fecha_hora')
                ->first();

            if ($cita) {
                /** @var Medico|null $medicoCita */
                $medicoCita = $cita->medico;
                /** @var Medico|null $medicoPaciente */
                $medicoPaciente = $cita->paciente?->medicoAsignado;
                $medicoRef = $medicoCita ?? $medicoPaciente;

                $nombreMed = $medicoRef ? $medicoRef->nombreParaMostrar() : '';
                $espMed = $medicoRef ? $medicoRef->especialidadParaMostrar() : '';

                $proximaCita = (object) [
                    'id' => $cita->id,
                    'fecha_hora' => $cita->fecha_hora,
                    'medico_nombre' => $nombreMed !== '' ? $nombreMed : 'Por asignar',
                    'especialidad' => $espMed !== '' ? $espMed : 'Sin asignar',
                    'estado' => $cita->estado,
                    'motivo' => $cita->motivo,
                ];
            }
        }

        if ($pacienteId && Schema::hasTable('diagnosticos')) {
            $totalDiagnosticos = Diagnostico::query()
                ->where('paciente_id', $pacienteId)
                ->count();

            $diagnosticosRecientes = Diagnostico::query()
                ->where('paciente_id', $pacienteId)
                ->with(['medico.usuario', 'cita'])
                ->orderByDesc('fecha_diagnostico')
                ->limit(5)
                ->get();
        }

        if ($pacienteId && Schema::hasTable('resultados')) {
            $resultados = Resultado::query()
                ->where('paciente_id', $pacienteId)
                ->orderByDesc('fecha_resultado')
                ->limit(6)
                ->get();

            $resultadosPendientes = Resultado::query()
                ->where('paciente_id', $pacienteId)
                ->whereIn('estado', ['atencion', 'anormal'])
                ->count();
        }

        return view('paciente.dashboard', compact(
            'usuario',
            'citasPendientes',
            'proximaCita',
            'resultados',
            'resultadosPendientes',
            'diagnosticosRecientes',
            'totalDiagnosticos'
        ));
    }
}
