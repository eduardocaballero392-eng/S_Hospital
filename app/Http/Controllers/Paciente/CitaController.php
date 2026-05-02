<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    //  Sin middleware auth — acceso público
    public function index()
    {
        $paciente = null;
        $usuario  = null;

        if (Auth::check()) {
            $usuario  = Auth::user();
            $paciente = $usuario->paciente ?? null;
        }

        return view('paciente.citas', compact('paciente', 'usuario'));
    }

    public function store(Request $request)
    {
        // Validación acorde a tu tabla citas
        $request->validate([
            'dni'        => 'required|string|max:8',
            'nombres'    => 'required|string|max:100',
            'apellidos'  => 'required|string|max:100',
            'fecha_nac'  => 'required|date',
            'genero'     => 'required|string',
            'telefono'   => 'required|string|min:9|max:9',
            'direccion'  => 'nullable|string|max:255',
            'email'      => 'nullable|email|max:150',
            'motivo'     => 'required|string|max:255',
            'tipo'       => 'required|string',
            'fecha_hora' => 'required|date|after_or_equal:today',
        ], [
            'dni.required'      => 'El DNI es obligatorio.',
            'nombres.required'  => 'Los nombres son obligatorios.',
            'apellidos.required'=> 'Los apellidos son obligatorios.',
            'telefono.min'      => 'El teléfono debe tener 9 dígitos.',
            'motivo.required'   => 'El motivo de la cita es obligatorio.',
            'tipo.required'     => 'El tipo de cita es obligatorio.',
            'fecha_hora.required' => 'La fecha y hora son obligatorias.',
            'fecha_hora.after_or_equal' => 'La fecha debe ser hoy o una fecha futura.',
        ]);

        // Guardar o actualizar paciente por DNI
        $paciente = Paciente::updateOrCreate(
            ['DNI' => $request->dni],
            [
                'nombre'    => $request->nombres,
                'apellido'  => $request->apellidos,
                'fecha_nac' => $request->fecha_nac,
                'genero'    => $request->genero,
                'telefono'  => $request->telefono,
                'email'     => $request->email,
                'direccion' => $request->direccion,
            ]
        );

        // Guardar cita acorde a tu tabla
        Cita::create([
            'paciente_id' => $paciente->id,
            'medico_id'   => null,   // el admin asignará el médico después
            'sala_id'     => null,   // el admin asignará la sala después
            'fecha_hora'  => $request->fecha_hora,
            'estado'      => 'pendiente',
            'motivo'      => $request->motivo,
            'tipo'        => $request->tipo,
        ]);

        return back()->with('success', '¡Cita agendada exitosamente! Nos contactaremos contigo pronto.');
    }
}