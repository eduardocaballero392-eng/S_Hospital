<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use App\Models\Resultado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ResultadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $usuario = Auth::user();
        $resultadosForJs = [];
        $pacienteId = $usuario->resolvePacienteId();

        if ($pacienteId && Schema::hasTable('resultados')) {
            $resultadosForJs = Resultado::query()
                ->where('paciente_id', $pacienteId)
                ->orderByDesc('fecha_resultado')
                ->get()
                ->map(function (Resultado $r) {
                    return [
                        'id' => $r->id,
                        'nombre' => $r->nombre,
                        'descripcion' => $r->descripcion ?? '',
                        'tipo' => $r->tipo ?: 'laboratorio',
                        'especialista' => $r->especialista ?? '—',
                        'servicio' => $r->servicio ?? '—',
                        'fecha' => $r->fecha_resultado
                            ? $r->fecha_resultado->locale('es')->translatedFormat('d M Y')
                            : '',
                        'estado' => $r->estado ?: 'normal',
                        'detalle' => $r->detalle ?? '',
                    ];
                })
                ->values()
                ->all();
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['data' => $resultadosForJs]);
        }

        return view('paciente.resultados', compact('usuario', 'resultadosForJs'));
    }
}
