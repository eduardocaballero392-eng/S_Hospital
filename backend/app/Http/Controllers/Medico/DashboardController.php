<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Cita;
use App\Models\Medico;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $usuario = Auth::user();
        $medico = Medico::where('usuario_id', $usuario->id)->first();

        $citasHoy = [];
        $citasPendientes = [];
        $totalPacientes = 0;

        if ($medico) {
            $citasHoy = Cita::where('medico_id', $medico->id)
                ->whereDate('fecha_hora', today())
                ->orderBy('fecha_hora')
                ->get();

            $citasPendientes = Cita::where('medico_id', $medico->id)
                ->where('estado', 'pendiente')
                ->orderBy('fecha_hora')
                ->take(5)
                ->get();

            $totalPacientes = Cita::where('medico_id', $medico->id)
                ->distinct('paciente_id')
                ->count('paciente_id');
        }

        return view('medico.dashboard', compact(
            'usuario',
            'medico',
            'citasHoy',
            'citasPendientes',
            'totalPacientes'
        ));
    }
}