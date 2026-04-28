<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
    ];

    protected $hidden = [
        'contrasena',
    ];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}