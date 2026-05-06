<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller; 
use App\Models\Reclamacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReclamacionController extends Controller
{
    public function index()
    {
        return view('reclamaciones.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'         => 'required|string|max:100',
            'apellido'       => 'required|string|max:100',
            'tipo_documento' => 'required|string',
            'nro_documento'  => 'required|string|max:15',
            'email'          => 'required|email|max:150',
            'telefono'       => 'nullable|string|max:9',
            'direccion'      => 'nullable|string|max:255',
            'tipo_reclamo'   => 'required|string',
            'detalle'        => 'required|string|max:1000',
        ], [
            'nombre.required'         => 'El nombre es obligatorio.',
            'apellido.required'       => 'El apellido es obligatorio.',
            'tipo_documento.required' => 'El tipo de documento es obligatorio.',
            'nro_documento.required'  => 'El número de documento es obligatorio.',
            'email.required'          => 'El correo es obligatorio.',
            'tipo_reclamo.required'   => 'Selecciona el tipo de reclamo.',
            'detalle.required'        => 'El detalle de la reclamación es obligatorio.',
        ]);

        // Guardar en BD
        Reclamacion::create($request->all());

        // Enviar correo al laboratorio
        try {
            Mail::send('emails.reclamacion', ['data' => $request->all()], function ($m) use ($request) {
                $m->to(config('mail.from.address'))
                  ->subject('Nueva Reclamación - ' . $request->nombre . ' ' . $request->apellido);
            });
        } catch (\Exception $e) {
            \Log::error('Error enviando correo reclamación: ' . $e->getMessage());
        }

        return back()->with('success', '¡Tu reclamación ha sido enviada exitosamente! Nos contactaremos contigo en un plazo máximo de 15 días hábiles.');
    }
}