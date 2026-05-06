<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reclamacion extends Model
{
    protected $table = 'reclamaciones';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido',
        'tipo_documento',
        'nro_documento',
        'email',
        'telefono',
        'direccion',
        'tipo_reclamo',
        'detalle',
    ];
}