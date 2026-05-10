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

    // ========== RELACIONES NUEVAS ==========
    
    /**
     * Relación con las citas
     */
    public function citas()
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }
    
    /**
     * Relación con los diagnósticos
     */
    public function diagnosticos()
    {
        return $this->hasMany(Diagnostico::class, 'paciente_id');
    }
    
    /**
     * Relación con las recetas
     */
    public function recetas()
    {
        return $this->hasMany(Receta::class, 'paciente_id');
    }
    
    /**
     * Relación con los resultados
     */
    public function resultados()
    {
        return $this->hasMany(Resultado::class, 'paciente_id');
    }
    
    // ========== ACCESOR ==========
    
    /**
     * Obtener el nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellido;
    }
    
    /**
     * Calcular edad a partir de fecha de nacimiento
     */
    public function getEdadAttribute()
    {
        if ($this->fecha_nac) {
            return \Carbon\Carbon::parse($this->fecha_nac)->age;
        }
        return null;
    }
}