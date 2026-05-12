<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CitaController extends Controller
{
    public function index()
    {
        $citas = Cita::with(['paciente', 'medico'])
            ->orderBy('fecha_hora', 'desc')
            ->paginate(20);
        
        return view('admin.citas.index', compact('citas'));
    }
    
    public function show($id)
    {
        $cita = Cita::with(['paciente', 'medico'])->findOrFail($id);
        return view('admin.citas.show', compact('cita'));
    }
    
    public function edit($id)
    {
        $cita = Cita::with(['paciente', 'medico'])->findOrFail($id);
        $pacientes = Paciente::all();
        $medicos = Medico::all();
        return view('admin.citas.edit', compact('cita', 'pacientes', 'medicos'));
    }
    
    public function update(Request $request, $id)
    {
        $cita = Cita::findOrFail($id);
        
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'fecha_hora' => 'required|date',
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada',
            'motivo' => 'nullable|string',
        ]);
        
        $cita->update([
            'paciente_id' => $request->paciente_id,
            'medico_id' => $request->medico_id,
            'fecha_hora' => $request->fecha_hora,
            'estado' => $request->estado,
            'motivo' => $request->motivo,
        ]);

        if (Schema::hasColumn('pacientes', 'medico_asignado_id')) {
            Paciente::whereKey($request->paciente_id)->update([
                'medico_asignado_id' => $request->medico_id,
            ]);
        }

        return redirect()->route('admin.citas.index')
            ->with('success', 'Cita actualizada correctamente');
    }
    
    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->delete();
        
        return response()->json(['success' => true]);
    }
}