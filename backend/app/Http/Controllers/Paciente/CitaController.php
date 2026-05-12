<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use App\Models\Rol;
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
    $citas = Cita::with('paciente')->orderBy('fecha_hora', 'desc')->get();
    return response()->json($citas);
}

    public function store(Request $request)
    {
        $fechaMaximaParaMenor = Carbon::today()->subYears(18)->format('Y-m-d');
        $esInvitado = !Auth::check();

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
            'email.required'            => 'El correo electrónico es obligatorio para agendar tu cita.',
            'fecha_nac.before_or_equal' => 'Debes ser mayor de edad (18+) para agendar una cita.',
            'fecha_hora.required'       => 'La fecha y hora son obligatorias.',
            'fecha_hora.after_or_equal' => 'La fecha debe ser hoy o una fecha futura.',
            'fecha_hora.unique'         => 'Ya existe una cita reservada para esa misma fecha y hora. Elige otro horario.',
        ]);

        $validator->sometimes('email', 'required', function () use ($esInvitado) {
            return $esInvitado;
        });

        $validator->validate();

        // Resolver usuario vinculado antes de guardar paciente para evitar errores
        // de columnas obligatorias (usuario_id) en algunos esquemas.
        $usuarioVinculado = Auth::user();
        $cuentaNueva = false;
        $credenciales = null;

        if (!$usuarioVinculado && $request->filled('email')) {
            $usuarioVinculado = Usuario::where('email', $request->email)->first();

            if (!$usuarioVinculado) {
                $usuarioVinculado = Usuario::create([
                    'nombre'      => $request->nombres . ' ' . $request->apellidos,
                    'email'       => $request->email,
                    'contrasena'  => Hash::make($request->dni),
                    'rol_id'      => Rol::PACIENTE,
                    'estado'      => 'activo',
                    
                ]);

                $cuentaNueva  = true;
                $credenciales = [
                    'email'      => $request->email,
                    'contrasena' => $request->dni,
                ];
            }
        }

        // ──────────────────────────────────────────
        // 1️⃣ Guardar o actualizar Paciente por DNI
        // ──────────────────────────────────────────
        $paciente = Paciente::updateOrCreate(
            ['DNI' => $request->dni],
            [
                'usuario_id' => $usuarioVinculado->id ?? null,
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
        // 2️ Crear cuenta User automáticamente
        //    Solo si tiene email y no existe cuenta
        // ──────────────────────────────────────────
        if ($usuarioVinculado) {
            // Sincronizar ambos lados de la relación usuario <-> paciente.
            if (!$paciente->usuario_id || $paciente->usuario_id != $usuarioVinculado->id) {
                $paciente->update(['usuario_id' => $usuarioVinculado->id]);
            }

           

            // Enviar correo solo cuando la cuenta se creó en este flujo.
            if ($cuentaNueva && $request->filled('email')) {
                try {
                    Mail::to($request->email)
                        ->send(new CredencialesMail($usuarioVinculado, $request->dni));
                } catch (\Exception $e) {
                    // Si el mail falla, no rompemos el flujo
                    \Log::error('Error enviando correo credenciales: ' . $e->getMessage());
                }
            }
        }

        // ──────────────────────────────────────────
        // 3️⃣ Guardar la Cita (fecha/hora elegidas = zona horaria del laboratorio, ver APP_TIMEZONE)
        // ──────────────────────────────────────────
        $fechaHora = Carbon::parse($request->fecha_hora, config('app.timezone'));

        Cita::create([
            'paciente_id' => $paciente->id,
            'medico_id'   => null,
            'sala_id'     => null,
            'fecha_hora'  => $fechaHora,
            'estado'      => 'pendiente',
            'motivo'      => $request->motivo,
            'tipo'        => $request->tipo,
        ]);

        // ──────────────────────────────────────────
        // 4️⃣ Respuesta: JSON (API) o redirección con flash (formulario web)
        // ──────────────────────────────────────────
        if ($request->is('api/*')) {
            return response()->json([
                'success'        => true,
                'mensaje'        => 'Cita agendada correctamente',
                'cuentaNueva'    => $cuentaNueva,
                'credenciales'   => $credenciales,
                'nombrePaciente' => $request->nombres,
            ], 201);
        }

        return redirect()
            ->route('paciente.citas')
            ->with('success', true)
            ->with('nombrePaciente', $request->nombres);
    }

    
    //consultar una cita especifica
    public function show($id)
    {
        $cita = Cita::with('paciente')->find($id);
    
        if (!$cita) {
            return response()->json(['mensaje' => 'Cita no encontrada'], 404);
        }
    
        return response()->json($cita);
    }


    //actualizar una cita  specifica

    public function update(Request $request, $id)
{
    $cita = Cita::find($id);

    if (!$cita) {
        return response()->json(['mensaje' => 'Cita no encontrada'], 404);
    }

    $cita->update($request->only([
        'medico_id', 'sala_id', 'fecha_hora', 'estado', 'motivo', 'tipo'
    ]));

    return response()->json([
        'success' => true,
        'mensaje' => 'Cita actualizada correctamente',
        'cita'    => $cita
    ]);
}


//eliminar una cita especifica
public function destroy($id)
{
    $cita = Cita::find($id);

    if (!$cita) {
        return response()->json(['mensaje' => 'Cita no encontrada'], 404);
    }

    $cita->delete();

    return response()->json([
        'success' => true,
        'mensaje' => 'Cita eliminada correctamente'
    ]);
}


}