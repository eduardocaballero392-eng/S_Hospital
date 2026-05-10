<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Paciente;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    // Desactivar timestamps automáticos
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'email',
        'contrasena',
        'rol_id',
        'estado',
        'paciente_id',
    ];

    protected $hidden = [
        'contrasena',
    ];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function medico()
    {
        return $this->hasOne(Medico::class, 'usuario_id');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }
}