<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use App\Models\User;
use App\Models\Medico;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PacienteController extends Controller
{
    public function index()
    {
        $pacientes = Paciente::with(['usuario', 'medicoAsignado'])
            ->orderBy('id', 'desc')
            ->paginate(15);
        
        $medicos = Medico::all();
        
        return view('admin.pacientes.index', compact('pacientes', 'medicos'));
    }
    
    public function create()
    {
        $medicos = Medico::all();
        return view('admin.pacientes.create', compact('medicos'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'DNI' => 'required|string|unique:pacientes',
            'email' => 'required|email|unique:usuarios,email',
            'telefono' => 'required|string',
            'fecha_nac' => 'required|date',
            'genero' => 'required|in:M,F',
            'direccion' => 'nullable|string',
            'medico_asignado_id' => 'nullable|exists:medicos,id',
        ]);
        
        // Crear usuario
        $usuario = User::create([
            'nombre' => $request->nombre . ' ' . $request->apellido,
            'email' => $request->email,
            'contrasena' => Hash::make($request->DNI),
            'rol_id' => Rol::PACIENTE,
            'estado' => 1,
        ]);
        
        // Crear paciente
        $paciente = Paciente::create([
            'usuario_id' => $usuario->id,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'DNI' => $request->DNI,
            'fecha_nac' => $request->fecha_nac,
            'genero' => $request->genero,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'medico_asignado_id' => $request->medico_asignado_id,
        ]);
        
        return redirect()->route('admin.pacientes.index')
            ->with('success', 'Paciente creado correctamente');
    }
    
    public function edit($id)
    {
        $paciente = Paciente::with('usuario')->findOrFail($id);
        $medicos = Medico::all();
        return view('admin.pacientes.edit', compact('paciente', 'medicos'));
    }
    
    public function update(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'DNI' => 'required|string|unique:pacientes,DNI,' . $id,
            'telefono' => 'required|string',
            'fecha_nac' => 'required|date',
            'genero' => 'required|in:M,F',
            'direccion' => 'nullable|string',
            'medico_asignado_id' => 'nullable|exists:medicos,id',
            'estado' => 'required|in:0,1',
        ]);
        
        $paciente->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'DNI' => $request->DNI,
            'fecha_nac' => $request->fecha_nac,
            'genero' => $request->genero,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'medico_asignado_id' => $request->medico_asignado_id,
        ]);
        
        if ($paciente->usuario) {
            $paciente->usuario->update([
                'nombre' => $request->nombre . ' ' . $request->apellido,
                'estado' => $request->estado,
            ]);
        }
        
        return redirect()->route('admin.pacientes.index')
            ->with('success', 'Paciente actualizado correctamente');
    }
    
    public function destroy($id)
    {
        $paciente = Paciente::findOrFail($id);
        
        if ($paciente->usuario) {
            $paciente->usuario->delete();
        }
        
        $paciente->delete();
        
        return response()->json(['success' => true]);
    }
    
    public function show($id)
    {
        $paciente = Paciente::with(['usuario', 'medicoAsignado', 'citas', 'diagnosticos'])
            ->findOrFail($id);
        
        return view('admin.pacientes.show', compact('paciente'));
    }
}