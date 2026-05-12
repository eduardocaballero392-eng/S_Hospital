<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Diagnostico;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class PacienteController extends Controller
{
    public function index()
    {
        $medico = Medico::where('usuario_id', Auth::id())->first();

        if (!$medico) {
            return view('medico.pacientes', [
                'pacientes' => collect(),
                'totalPacientes' => 0,
                'pacientesActivos' => 0,
                'nuevosPacientes' => 0,
                'diagnosticosRegistrados' => 0,
            ])->with('warning', 'Tu cuenta no tiene una ficha en la tabla de médicos. Creá o vinculá el registro (usuario_id = tu id de usuario) para ver pacientes asociados a tus citas.');
        }

        $pacienteIds = Cita::query()
            ->paraMedico($medico->id)
            ->pluck('paciente_id')
            ->merge(
                Schema::hasColumn('pacientes', 'medico_asignado_id')
                    ? Paciente::query()->where('medico_asignado_id', $medico->id)->pluck('id')
                    : collect()
            )
            ->unique()
            ->filter();

        $pacientes = $pacienteIds->isEmpty()
            ? collect()
            : Paciente::whereIn('id', $pacienteIds)->orderBy('nombre')->get();

        $totalPacientes = $pacientes->count();
        $pacientesActivos = $totalPacientes;
        $nuevosPacientes = 0;

        $diagnosticosRegistrados = 0;
        if (Schema::hasTable('diagnosticos')) {
            $diagnosticosRegistrados = Diagnostico::where('medico_id', $medico->id)->count();
        }

        foreach ($pacientes as $paciente) {
            $ultimaCita = Cita::query()
                ->paraMedico($medico->id)
                ->where('paciente_id', $paciente->id)
                ->orderBy('fecha_hora', 'desc')
                ->first();
            $paciente->ultima_cita = $ultimaCita ? $ultimaCita->fecha_hora : null;

            $proximaCita = Cita::query()
                ->paraMedico($medico->id)
                ->where('paciente_id', $paciente->id)
                ->where('fecha_hora', '>=', now())
                ->orderBy('fecha_hora', 'asc')
                ->first();
            $paciente->proxima_cita = $proximaCita ? $proximaCita->fecha_hora : null;

            if ($paciente->fecha_nac) {
                $paciente->edad = \Carbon\Carbon::parse($paciente->fecha_nac)->age;
            } else {
                $paciente->edad = null;
            }
        }

        return view('medico.pacientes', compact('pacientes', 'totalPacientes', 'pacientesActivos', 'nuevosPacientes', 'diagnosticosRegistrados'));
    }

    public function detalle($id)
    {
        $paciente = Paciente::findOrFail($id);

        return response()->json(['success' => true, 'paciente' => $paciente]);
    }

    public function historial($id)
    {
        return redirect()->route('medico.historial', ['paciente' => $id]);
    }
}

