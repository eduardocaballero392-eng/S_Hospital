<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Medico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    public function index()
    {
        $medico = Medico::where('usuario_id', Auth::id())->first();

        $citas = $medico
            ? Cita::where('medico_id', $medico->id)
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
        $cita = Cita::findOrFail($id);
        $cita->estado = 'confirmada';
        $cita->save();
        
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
}