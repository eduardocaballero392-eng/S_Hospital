<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use App\Models\Cita;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Mail\CredencialesMail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CitaController extends Controller
{
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
        $fechaMaximaParaMenor = Carbon::today()->subYears(18)->format('Y-m-d');

        $validator = Validator::make($request->all(), [
            'dni'        => 'required|string|max:8',
            'nombres'    => 'required|string|max:100',
            'apellidos'  => 'required|string|max:100',
            'fecha_nac'  => 'required|date|before_or_equal:' . $fechaMaximaParaMenor,
            'genero'     => 'required|string',
            'telefono'   => 'required|string|min:9|max:9',
            'direccion'  => 'nullable|string|max:255',
            'email'      => 'nullable|email|max:150',
            'motivo'     => 'required|string|max:255',
            'tipo'       => 'required|string',
            'fecha_hora' => [
                'required',
                'date',
                'after_or_equal:today',
                // Evita que alguien reserve exactamente la misma fecha+hora.
                Rule::unique('citas', 'fecha_hora'),
            ],
        ], [
            'dni.required'              => 'El DNI es obligatorio.',
            'nombres.required'          => 'Los nombres son obligatorios.',
            'apellidos.required'        => 'Los apellidos son obligatorios.',
            'telefono.min'              => 'El teléfono debe tener 9 dígitos.',
            'motivo.required'           => 'El motivo de la cita es obligatorio.',
            'tipo.required'             => 'El tipo de cita es obligatorio.',
            'fecha_nac.before_or_equal' => 'Debes ser mayor de edad (18+) para agendar una cita.',
            'fecha_hora.required'       => 'La fecha y hora son obligatorias.',
            'fecha_hora.after_or_equal' => 'La fecha debe ser hoy o una fecha futura.',
            'fecha_hora.unique'         => 'Ya existe una cita reservada para esa misma fecha y hora. Elige otro horario.',
        ]);

        $validator->validate();

        // ──────────────────────────────────────────
        // 1️⃣ Guardar o actualizar Paciente por DNI
        // ──────────────────────────────────────────
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

        // ──────────────────────────────────────────
        // 2️⃣ Crear cuenta User automáticamente
        //    Solo si tiene email y no existe cuenta
        // ──────────────────────────────────────────
        $cuentaNueva = false;
        $credenciales = null;

        if ($request->filled('email')) {
            $userExistente = Usuario::where('email', $request->email)->first();

            if (!$userExistente) {
                // Crear usuario con DNI como contraseña temporal
                $nuevoUser = Usuario::create([
                    'nombre'        => $request->nombres . ' ' . $request->apellidos,
                    'email'       => $request->email,
                    'contrasena'    => Hash::make($request->dni),
                    'rol_id'        => 2,   // ajusta según tu columna de roles
                    'estado'        => 'activo',
                    'paciente_id' => $paciente->id, // vincula User ↔ Paciente
                ]);

                // Vincular también desde el lado del Paciente
                $paciente->update(['user_id' => $nuevoUser->id]);

                $cuentaNueva  = true;
                $credenciales = [
                    'email'      => $request->email,
                    'contrasena' => $request->dni,
                ];

                // Enviar correo con credenciales
                try {
                    Mail::to($request->email)
                        ->send(new CredencialesMail($nuevoUser, $request->dni));
                } catch (\Exception $e) {
                    // Si el mail falla, no rompemos el flujo
                    \Log::error('Error enviando correo credenciales: ' . $e->getMessage());
                }
            }
        }

        // ──────────────────────────────────────────
        // 3️⃣ Guardar la Cita
        // ──────────────────────────────────────────
        Cita::create([
            'paciente_id' => $paciente->id,
            'medico_id'   => null,
            'sala_id'     => null,
            'fecha_hora'  => $request->fecha_hora,
            'estado'      => 'pendiente',
            'motivo'      => $request->motivo,
            'tipo'        => $request->tipo,
        ]);

        // ──────────────────────────────────────────
        // 4️⃣ Redirigir con datos de confirmación
        // ──────────────────────────────────────────
        return back()
        ->with('success', true)
        ->with('cuentaNueva', $cuentaNueva)
        ->with('credenciales', $credenciales)
        ->with('nombrePaciente', $request->nombres);
    }

    
    
}