<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'DNI',
        'nombre',
        'apellido',
        'fecha_nac',
        'genero',
        'telefono',
        'email',
        'direccion',
        'medico_asignado_id',
    ];

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación con médico asignado
    public function medicoAsignado()
    {
        return $this->belongsTo(Medico::class, 'medico_asignado_id');
    }

    // Relación con citas
    public function citas()
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }

    // Relación con diagnósticos
    public function diagnosticos()
    {
        return $this->hasMany(Diagnostico::class, 'paciente_id');
    }

    // Relación con resultados
    public function resultados()
    {
        return $this->hasMany(Resultado::class, 'paciente_id');
    }

    // Accesor para nombre completo
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellido;
    }

    // Accesor para edad
    public function getEdadAttribute()
    {
        if ($this->fecha_nac) {
            return \Carbon\Carbon::parse($this->fecha_nac)->age;
        }
        return null;
    }
}