<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'citas';

    public $timestamps = false;

    protected $fillable = [
        'paciente_id',
        'medico_id',
        'sala_id',
        'fecha_hora',
        'estado',
        'motivo',
        'tipo',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}