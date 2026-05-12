<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Diagnostico;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DiagnosticoController extends Controller
{
    private function medicoActual(): ?Medico
    {
        return Medico::where('usuario_id', Auth::id())->first();
    }

    private function pacientesRelacionados(Medico $medico)
    {
        return Paciente::query()
            ->where(function ($q) use ($medico) {
                $q->whereHas('citas', function ($cq) use ($medico) {
                    $cq->where('medico_id', $medico->id);
                });
                if (Schema::hasColumn('pacientes', 'medico_asignado_id')) {
                    $q->orWhere('medico_asignado_id', $medico->id);
                }
            })
            ->orderBy('nombre')
            ->get();
    }

    private function citasPorPacienteJson(Medico $medico, $pacientes): array
    {
        $out = [];
        foreach ($pacientes as $paciente) {
            $out[$paciente->id] = Cita::query()
                ->where('paciente_id', $paciente->id)
                ->where(function ($q) use ($medico) {
                    $q->where('medico_id', $medico->id)->orWhereNull('medico_id');
                })
                ->orderByDesc('fecha_hora')
                ->limit(30)
                ->get()
                ->map(function (Cita $c) {
                    return [
                        'id' => $c->id,
                        'label' => $c->fecha_hora->format('d/m/Y H:i') . ' — ' . ($c->motivo ?: ($c->tipo ?: 'Cita')),
                        'estado' => $c->estado,
                    ];
                })
                ->values()
                ->all();
        }

        return $out;
    }

    public function index()
    {
        $user = Auth::user();
        $medico = $user->medico;

        if (!$medico) {
            $medico = Medico::create([
                'usuario_id' => $user->id,
                'nombre' => $user->nombre,
                'especialidad' => 'Medicina General',
            ]);
        }

        $diagnosticos = collect();
        if (Schema::hasTable('diagnosticos')) {
            $diagnosticos = Diagnostico::where('medico_id', $medico->id)
                ->with(['paciente', 'cita'])
                ->orderByDesc('fecha_diagnostico')
                ->get();
        }

        $pacientes = $this->pacientesRelacionados($medico);

        return view('medico.diagnosticos', compact('diagnosticos', 'pacientes'));
    }

    public function crear(Request $request)
    {
        $medico = $this->medicoActual();
        if (!$medico) {
            return redirect()->route('medico.diagnosticos')->with('error', 'Configuración de médico incompleta.');
        }

        $pacientes = $this->pacientesRelacionados($medico);
        $pacienteId = (int) $request->get('paciente', 0);
        $citasPorPaciente = $this->citasPorPacienteJson($medico, $pacientes);

        return view('medico.diagnosticos-crear', compact('pacientes', 'pacienteId', 'citasPorPaciente'));
    }

    public function store(Request $request)
{
    $request->validate([
        'paciente_id' => 'required|exists:pacientes,id',
        'nombre' => 'required|string|max:255',
        'descripcion' => 'required|string',
        'fecha_diagnostico' => 'required|date',
        'tipo' => 'required|in:cronico,agudo,preventivo',
    ]);

    $medico = $this->medicoActual();
    if (!$medico) {
        return redirect()->back()->with('error', 'Médico no encontrado');
    }

    if (!Schema::hasTable('diagnosticos')) {
        return redirect()->back()->with('error', 'La tabla de diagnósticos no está disponible.');
    }

    Diagnostico::create([
        'paciente_id' => $request->paciente_id,
        'medico_id' => $medico->id,
        // 'cita_id' => $request->input('cita_id'),  ← ELIMINADA
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'fecha_diagnostico' => $request->fecha_diagnostico,
        'tipo' => $request->input('tipo', 'preventivo'),
        'created_at' => now(),
    ]);

    return redirect()->route('medico.diagnosticos')
        ->with('success', 'Diagnóstico registrado correctamente');
}

    public function show($id)
    {
        $diagnostico = Diagnostico::with(['paciente'])->findOrFail($id);

        $medico = $this->medicoActual();
        if (!$medico || $diagnostico->medico_id != $medico->id) {
            abort(403, 'No autorizado');
        }

        return view('medico.diagnosticos-ver', compact('diagnostico'));
    }

    public function edit($id)
    {
        $diagnostico = Diagnostico::findOrFail($id);

        $medico = $this->medicoActual();
        if (!$medico || $diagnostico->medico_id != $medico->id) {
            abort(403, 'No autorizado');
        }

        $pacientes = $this->pacientesRelacionados($medico);
        $citasPorPaciente = $this->citasPorPacienteJson($medico, $pacientes);

        return view('medico.diagnosticos-editar', compact('diagnostico', 'pacientes', 'citasPorPaciente'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha_diagnostico' => 'required|date',
            'tipo' => 'nullable|in:cronico,agudo,preventivo',
            
        ]);

        $diagnostico = Diagnostico::findOrFail($id);

        $medico = $this->medicoActual();
        if (!$medico || $diagnostico->medico_id != $medico->id) {
            abort(403, 'No autorizado');
        }

        if ($request->filled('cita_id')) {
            $cita = Cita::find($request->cita_id);
            if (!$cita || (int) $cita->paciente_id !== (int) $request->paciente_id) {
                return redirect()->back()->withInput()->withErrors(['cita_id' => 'La cita seleccionada no corresponde al paciente.']);
            }
        }

        $diagnostico->update([
            'paciente_id' => $request->paciente_id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha_diagnostico' => $request->fecha_diagnostico,
            'tipo' => $request->input('tipo', $diagnostico->tipo ?? 'preventivo'),
           
        ]);

        return redirect()->route('medico.diagnosticos')
            ->with('success', 'Diagnóstico actualizado correctamente');
    }

    public function destroy($id)
    {
        $diagnostico = Diagnostico::findOrFail($id);

        $medico = $this->medicoActual();
        if (!$medico || $diagnostico->medico_id != $medico->id) {
            abort(403, 'No autorizado');
        }

        $diagnostico->delete();

        return redirect()->route('medico.diagnosticos')
            ->with('success', 'Diagnóstico eliminado correctamente');
    }
}
