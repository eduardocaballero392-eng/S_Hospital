<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MedicoController extends Controller
{
    public function index()
    {
        // Corregido: quitamos 'pacientesAsignados' que no existe en el modelo
        $medicos = Medico::with('usuario')
            ->orderBy('id', 'desc')
            ->paginate(15);
        
        return view('admin.medicos.index', compact('medicos'));
    }
    
    public function create()
    {
        return view('admin.medicos.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'especialidad' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'telefono' => 'required|string',
        ]);
        
        // Crear usuario para el médico
        $usuario = User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'contrasena' => Hash::make($request->email),
            'rol_id' => Rol::MEDICO,
            'estado' => 'activo',
        ]);
        
        // Crear médico
        $medico = Medico::create([
            'usuario_id' => $usuario->id,
            'nombre' => $request->nombre,
            'especialidad' => $request->especialidad,
            'telefono' => $request->telefono,
            'email' => $request->email,
        ]);
        
        return redirect()->route('admin.medicos.index')
            ->with('success', 'Médico creado correctamente');
    }
    
    public function edit($id)
    {
        $medico = Medico::with('usuario')->findOrFail($id);
        return view('admin.medicos.edit', compact('medico'));
    }
    
    public function update(Request $request, $id)
    {
        $medico = Medico::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'especialidad' => 'required|string|max:255',
            'telefono' => 'required|string',
            'estado' => 'required|in:0,1',
        ]);
        
        $medico->update([
            'nombre' => $request->nombre,
            'especialidad' => $request->especialidad,
            'telefono' => $request->telefono,
        ]);

        if (! $medico->usuario && $medico->email) {
            $vinculo = User::query()
                ->where('email', $medico->email)
                ->where('rol_id', Rol::MEDICO)
                ->first();
            if ($vinculo) {
                $medico->usuario_id = $vinculo->id;
                $medico->save();
                $medico->load('usuario');
            }
        }

        $estadoUsuario = ((string) $request->estado === '1') ? 'activo' : 'inactivo';

        if ($medico->usuario) {
            $medico->usuario->update([
                'nombre' => $request->nombre,
                'estado' => $estadoUsuario,
            ]);
        }
        
        return redirect()->route('admin.medicos.index')
            ->with('success', 'Médico actualizado correctamente');
    }
    
    public function destroy($id)
    {
        $medico = Medico::findOrFail($id);
        
        if ($medico->usuario) {
            $medico->usuario->delete();
        }
        
        $medico->delete();
        
        return response()->json(['success' => true]);
    }
    
    public function show($id)
    {
        $medico = Medico::with('usuario')
            ->findOrFail($id);
        
        return view('admin.medicos.show', compact('medico'));
    }
}