<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Diagnostico;
use App\Models\Paciente;
use App\Models\Medico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiagnosticoController extends Controller
{
    /**
     * Lista de diagnósticos del médico
     */
    public function index()
    {
        $user = Auth::user();
        $medico = $user->medico;
        
        // Si no existe médico, crearlo automáticamente
        if (!$medico) {
            $medico = Medico::create([
                'usuario_id' => $user->id,
                'nombre' => $user->nombre,
                'especialidad' => 'Medicina General',
            ]);
        }
        
        // Obtener diagnósticos del médico
        $diagnosticos = Diagnostico::where('medico_id', $medico->id)
            ->with('paciente')
            ->orderBy('fecha_diagnostico', 'desc')
            ->get();
        
        // Obtener pacientes para el filtro
        $pacientes = Paciente::whereHas('citas', function($query) use ($medico) {
            $query->where('medico_id', $medico->id);
        })->get();
        
        return view('medico.diagnosticos', compact('diagnosticos', 'pacientes'));
    }
    
    /**
     * Formulario para crear diagnóstico
     */
    public function crear(Request $request)
    {
        $user = Auth::user();
        $medico = $user->medico;
        
        if (!$medico) {
            return redirect()->route('medico.diagnosticos')->with('error', 'Configuración de médico incompleta.');
        }
        
        $pacientes = Paciente::whereHas('citas', function($query) use ($medico) {
            $query->where('medico_id', $medico->id);
        })->get();
        
        $pacienteId = $request->get('paciente');
        
        return view('medico.diagnosticos-crear', compact('pacientes', 'pacienteId'));
    }
    
    /**
     * Guardar nuevo diagnóstico
     */
    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha_diagnostico' => 'required|date',
        ]);
        
        $user = Auth::user();
        $medico = $user->medico;
        
        if (!$medico) {
            return redirect()->back()->with('error', 'Médico no encontrado');
        }
        
        Diagnostico::create([
            'paciente_id' => $request->paciente_id,
            'medico_id' => $medico->id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha_diagnostico' => $request->fecha_diagnostico,
            'created_at' => now(),
        ]);
        
        return redirect()->route('medico.diagnosticos')
            ->with('success', 'Diagnóstico registrado correctamente');
    }
    
    /**
     * Ver detalle de diagnóstico
     */
    public function show($id)
    {
        $diagnostico = Diagnostico::with('paciente')->findOrFail($id);
        
        $user = Auth::user();
        $medico = $user->medico;
        
        if (!$medico || $diagnostico->medico_id != $medico->id) {
            abort(403, 'No autorizado');
        }
        
        return view('medico.diagnosticos-ver', compact('diagnostico'));
    }
    
    /**
     * Formulario para editar diagnóstico
     */
    public function edit($id)
    {
        $diagnostico = Diagnostico::findOrFail($id);
        
        $user = Auth::user();
        $medico = $user->medico;
        
        if (!$medico || $diagnostico->medico_id != $medico->id) {
            abort(403, 'No autorizado');
        }
        
        $pacientes = Paciente::whereHas('citas', function($query) use ($medico) {
            $query->where('medico_id', $medico->id);
        })->get();
        
        return view('medico.diagnosticos-editar', compact('diagnostico', 'pacientes'));
    }
    
    /**
     * Actualizar diagnóstico
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha_diagnostico' => 'required|date',
        ]);
        
        $diagnostico = Diagnostico::findOrFail($id);
        
        $user = Auth::user();
        $medico = $user->medico;
        
        if (!$medico || $diagnostico->medico_id != $medico->id) {
            abort(403, 'No autorizado');
        }
        
        $diagnostico->update([
            'paciente_id' => $request->paciente_id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha_diagnostico' => $request->fecha_diagnostico,
        ]);
        
        return redirect()->route('medico.diagnosticos')
            ->with('success', 'Diagnóstico actualizado correctamente');
    }
    
    /**
     * Eliminar diagnóstico
     */
    public function destroy($id)
    {
        $diagnostico = Diagnostico::findOrFail($id);
        
        $user = Auth::user();
        $medico = $user->medico;
        
        if (!$medico || $diagnostico->medico_id != $medico->id) {
            abort(403, 'No autorizado');
        }
        
        $diagnostico->delete();
        
        return redirect()->route('medico.diagnosticos')
            ->with('success', 'Diagnóstico eliminado correctamente');
    }
}