<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    protected $table = 'medicos';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'especialidad_id',
        'telefono',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'especialidad_id');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'medico_id');
    }
}