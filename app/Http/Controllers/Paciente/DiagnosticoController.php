<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DiagnosticoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $usuario = Auth::user();
        return view('paciente.diagnosticos', compact('usuario'));
    }
}