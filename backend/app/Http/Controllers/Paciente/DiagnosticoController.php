<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use App\Models\Diagnostico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DiagnosticoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $usuario = Auth::user();
        $diagnosticosForJs = [];

        $pacienteId = $usuario->resolvePacienteId();

        if ($pacienteId && Schema::hasTable('diagnosticos')) {
            $diagnosticosForJs = Diagnostico::query()
                ->where('paciente_id', $pacienteId)
                ->with(['medico.usuario', 'cita'])
                ->orderByDesc('fecha_diagnostico')
                ->get()
                ->map(function (Diagnostico $d) {
                    $fechaCita = $d->cita?->fecha_hora;
                    $med = $d->medico;

                    return [
                        'id' => $d->id,
                        'nombre' => $d->nombre,
                        'descripcion' => $d->descripcion ?? '',
                        'tipo' => $d->tipo ?: 'preventivo',
                        'especialista' => $med ? $med->nombreParaMostrar() : 'Médico',
                        'servicio' => $med && $med->especialidadParaMostrar() !== '' ? $med->especialidadParaMostrar() : 'Consulta',
                        'fecha' => $d->fecha_diagnostico
                            ? \Carbon\Carbon::parse($d->fecha_diagnostico)->locale('es')->translatedFormat('d M Y')
                            : '',
                        'fecha_cita' => $fechaCita
                            ? \Carbon\Carbon::parse($fechaCita)->locale('es')->translatedFormat('d M Y, H:i')
                            : null,
                        'detalle' => $d->descripcion ?? '',
                    ];
                })
                ->values()
                ->all();
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['data' => $diagnosticosForJs]);
        }

        return view('paciente.diagnosticos', compact('usuario', 'diagnosticosForJs'));
    }
}
