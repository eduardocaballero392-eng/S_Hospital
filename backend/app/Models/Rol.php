<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';

    public $timestamps = false;

    public const ADMIN = 1;

    public const PACIENTE = 2;

    public const MEDICO = 3;
}
