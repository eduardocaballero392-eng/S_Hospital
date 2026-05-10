<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnostico extends Model
{
    protected $table = 'diagnosticos';
    
    public $timestamps = false;
    
    protected $fillable = [
        'paciente_id',
        'medico_id',
        'nombre',
        'descripcion',
        'fecha_diagnostico',
        'created_at',
    ];
    
    protected $casts = [
        'fecha_diagnostico' => 'date',
        'created_at' => 'datetime',
    ];
    
    // Relación con paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }
    
    // Relación con médico
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }
}