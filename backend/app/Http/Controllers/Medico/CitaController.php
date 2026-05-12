<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class CitaController extends Controller
{
    public function index()
    {
        $medico = Medico::where('usuario_id', Auth::id())->first();

        $citas = $medico
            ? Cita::query()
                ->paraMedico($medico->id)
                ->with('paciente')
                ->orderBy('fecha_hora', 'desc')
                ->get()
            : collect();
        
        $totalCitas = $citas->count();
        $citasPendientes = $citas->where('estado', 'pendiente')->count();
        $citasConfirmadas = $citas->where('estado', 'confirmada')->count();
        $citasCompletadas = $citas->where('estado', 'completada')->count();
        
        return view('medico.citas', compact('citas', 'totalCitas', 'citasPendientes', 'citasConfirmadas', 'citasCompletadas'));
    }
    
    public function detalle($id)
    {
        $cita = Cita::with('paciente')->findOrFail($id);
        return response()->json(['success' => true, 'cita' => $cita]);
    }
    
    public function confirmar($id)
    {
        $medico = Medico::where('usuario_id', Auth::id())->firstOrFail();
        $cita = Cita::with('paciente')->findOrFail($id);

        if (! Cita::query()->paraMedico($medico->id)->whereKey($cita->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        if ($cita->medico_id === null) {
            $cita->medico_id = $medico->id;
        }

        $cita->estado = 'confirmada';
        $cita->save();

        $this->syncPacienteMedicoAsignado($cita, (int) $cita->medico_id);

        return response()->json(['success' => true]);
    }
    
    public function cancelar($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->estado = 'cancelada';
        $cita->save();
        
        return response()->json(['success' => true]);
    }
    
    public function completar($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->estado = 'completada';
        $cita->save();
        
        return response()->json(['success' => true]);
    }

    /**
     * Inicia atención: asigna al médico si la cita venía sin médico y marca confirmada.
     */
    public function atender(int $id)
    {
        $medico = Medico::where('usuario_id', Auth::id())->firstOrFail();
        $cita = Cita::with('paciente')->findOrFail($id);

        $visible = Cita::query()->paraMedico($medico->id)->whereKey($cita->id)->exists();
        if (!$visible) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        if ($cita->medico_id === null) {
            $cita->medico_id = $medico->id;
        }

        $cita->estado = 'confirmada';
        $cita->save();

        $this->syncPacienteMedicoAsignado($cita, (int) $cita->medico_id);

        return response()->json(['success' => true]);
    }

    protected function syncPacienteMedicoAsignado(Cita $cita, int $medicoId): void
    {
        if (! Schema::hasColumn('pacientes', 'medico_asignado_id') || ! $cita->paciente_id) {
            return;
        }

        Paciente::whereKey($cita->paciente_id)->update(['medico_asignado_id' => $medicoId]);
    }

    public function finalizar()
    {
        return response()->json(['success' => true]);
    }
}