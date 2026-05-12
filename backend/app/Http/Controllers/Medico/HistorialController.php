<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class HistorialController extends Controller
{
    public function index(Request $request)
    {
        $medico = Medico::where('usuario_id', Auth::id())->first();

        $pacientes = $medico
            ? Paciente::query()
                ->where(function ($q) use ($medico) {
                    $q->whereHas('citas', function ($cq) use ($medico) {
                        $cq->where('medico_id', $medico->id);
                    });
                    if (Schema::hasColumn('pacientes', 'medico_asignado_id')) {
                        $q->orWhere('medico_asignado_id', $medico->id);
                    }
                })
                ->orderBy('nombre')
                ->get()
            : collect();

        $pacienteSeleccionado = null;
        $diagnosticos = collect();
        $resultados = collect();
        $citas = collect();

        if ($request->has('paciente')) {
            $with = [
                'citas' => function ($query) {
                    $query->orderBy('fecha_hora', 'desc');
                },
            ];
            if (Schema::hasTable('diagnosticos')) {
                $with['diagnosticos'] = function ($query) {
                    $query->with('cita')->orderByDesc('fecha_diagnostico');
                };
            }
            if (Schema::hasTable('resultados')) {
                $with['resultados'] = function ($query) {
                    $query->orderBy('fecha_resultado', 'desc');
                };
            }

            $pacienteSeleccionado = Paciente::with($with)->find($request->paciente);

            if ($pacienteSeleccionado) {
                $diagnosticos = Schema::hasTable('diagnosticos')
                    ? $pacienteSeleccionado->diagnosticos
                    : collect();
                $resultados = Schema::hasTable('resultados')
                    ? $pacienteSeleccionado->resultados
                    : collect();
                $citas = $pacienteSeleccionado->citas;

                if ($pacienteSeleccionado->fecha_nac) {
                    $pacienteSeleccionado->edad = \Carbon\Carbon::parse($pacienteSeleccionado->fecha_nac)->age;
                }
            }
        }

        return view('medico.historial', compact('pacientes', 'pacienteSeleccionado', 'diagnosticos', 'resultados', 'citas'));
    }

    public function verPaciente($id)
    {
        return redirect()->route('medico.historial', ['paciente' => $id]);
    }
}
