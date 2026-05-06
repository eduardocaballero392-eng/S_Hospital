<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RecetaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $usuario = Auth::user();
        return view('paciente.recetas', compact('usuario'));
    }
}