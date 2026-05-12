<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reclamacion;
use Illuminate\Http\Request;

class ReclamacionController extends Controller
{
    public function index()
    {
        $reclamaciones = Reclamacion::orderBy('id', 'desc')->paginate(15);
        return view('admin.reclamaciones.index', compact('reclamaciones'));
    }

    public function show($id)
    {
        $reclamacion = Reclamacion::findOrFail($id);
        return view('admin.reclamaciones.show', compact('reclamacion'));
    }

    public function destroy($id)
    {
        $reclamacion = Reclamacion::findOrFail($id);
        $reclamacion->delete();
        
        return response()->json(['success' => true]);
    }
}