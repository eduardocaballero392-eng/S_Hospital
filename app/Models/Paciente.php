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
    ];

    // Relación con usuario (opcional)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}