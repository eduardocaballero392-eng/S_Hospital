<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistorialController extends Controller
{
    /**
     * Muestra el historial clínico con selector de paciente
     */
    public function index(Request $request)
    {
        $medico = Medico::where('usuario_id', Auth::id())->first();

        $pacientes = $medico
            ? Paciente::whereHas('citas', function ($query) use ($medico) {
                $query->where('medico_id', $medico->id);
            })->get()
            : collect();
        
        $pacienteSeleccionado = null;
        $diagnosticos = collect();
        $recetas = collect();
        $resultados = collect();
        $citas = collect();
        
        // Si hay un paciente seleccionado, cargar su historial
        if ($request->has('paciente')) {
            $pacienteSeleccionado = Paciente::with([
                'citas' => function($query) {
                    $query->orderBy('fecha_hora', 'desc');
                },
                'diagnosticos' => function($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'recetas' => function($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'resultados' => function($query) {
                    $query->orderBy('fecha_resultado', 'desc');
                }
            ])->find($request->paciente);
            
            if ($pacienteSeleccionado) {
                $diagnosticos = $pacienteSeleccionado->diagnosticos;
                $recetas = $pacienteSeleccionado->recetas;
                $resultados = $pacienteSeleccionado->resultados;
                $citas = $pacienteSeleccionado->citas;
                
                // Calcular edad
                if ($pacienteSeleccionado->fecha_nac) {
                    $pacienteSeleccionado->edad = \Carbon\Carbon::parse($pacienteSeleccionado->fecha_nac)->age;
                }
            }
        }
        
        return view('medico.historial', compact('pacientes', 'pacienteSeleccionado', 'diagnosticos', 'recetas', 'resultados', 'citas'));
    }
    
    /**
     * Ver historial de un paciente específico
     */
    public function verPaciente($id)
    {
        return redirect()->route('medico.historial', ['paciente' => $id]);
    }
}