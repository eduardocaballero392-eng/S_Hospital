<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with('rol')
            ->orderBy('id', 'desc')
            ->paginate(15);
        
        return view('admin.usuarios.index', compact('usuarios'));
    }
    
    public function create()
    {
        $roles = Rol::all();
        return view('admin.usuarios.create', compact('roles'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'contrasena' => 'required|string|min:6',
            'rol_id' => 'required|exists:roles,id',
        ]);
        
        $usuario = User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'contrasena' => Hash::make($request->contrasena),
            'rol_id' => $request->rol_id,
            'estado' => 1,
        ]);
        
        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado correctamente');
    }
    
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Rol::all();
        return view('admin.usuarios.edit', compact('usuario', 'roles'));
    }
    
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email,' . $id,
            'rol_id' => 'required|exists:roles,id',
            'estado' => 'required|in:0,1',
        ]);
        
        $usuario->update([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'rol_id' => $request->rol_id,
            'estado' => $request->estado,
        ]);
        
        if ($request->filled('contrasena')) {
            $usuario->update(['contrasena' => Hash::make($request->contrasena)]);
        }
        
        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente');
    }
    
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();
        
        return response()->json(['success' => true]);
    }
    
    public function show($id)
    {
        $usuario = User::with('rol')->findOrFail($id);
        return view('admin.usuarios.show', compact('usuario'));
    }
}