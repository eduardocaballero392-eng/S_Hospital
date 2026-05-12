<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    protected $table = 'medicos';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'nombre',
        'especialidad',
        'telefono',
        'email',
        'especialidad_id',
        'cmp',
    ];

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // ========== RELACIÓN CON PACIENTES ASIGNADOS ==========
    public function pacientesAsignados()
    {
        return $this->hasMany(Paciente::class, 'medico_asignado_id');
    }

    // Relación con citas
    public function citas()
    {
        return $this->hasMany(Cita::class, 'medico_id');
    }

    /**
     * Nombre para mostrar al paciente: columna `medicos.nombre` o, si está vacía, `usuarios.nombre`.
     */
    public function nombreParaMostrar(): string
    {
        $n = isset($this->attributes['nombre']) ? (string) $this->attributes['nombre'] : '';
        if (trim($n) !== '') {
            return trim($n);
        }
        $this->loadMissing('usuario');
        $u = (string) ($this->usuario?->nombre ?? '');

        return trim($u);
    }

    /**
     * Especialidad para UI (solo columna en `medicos`; no duplicamos en usuarios).
     */
    public function especialidadParaMostrar(): string
    {
        $e = isset($this->attributes['especialidad']) ? (string) $this->attributes['especialidad'] : '';

        return trim($e);
    }
}