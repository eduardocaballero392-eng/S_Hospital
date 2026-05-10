<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';

    protected $primaryKey = 'id';

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

    public $timestamps = false;

    // ========== AUTENTICACIÓN ==========
    
    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }
    
    // ========== RELACIONES ==========
    
    /**
     * Relación con el rol
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }
    
    /**
     * Relación con el paciente (si es paciente)
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }
    
    /**
     * Relación con el médico (si es médico)
     */
    public function medico()
    {
        return $this->hasOne(Medico::class, 'usuario_id');
    }
    
    // ========== MÉTODOS DE VERIFICACIÓN ==========
    
    /**
     * Verificar si el usuario es administrador
     */
    public function isAdmin()
    {
        return $this->rol_id === Rol::ADMIN; // 1
    }
    
    /**
     * Verificar si el usuario es paciente
     */
    public function isPaciente()
    {
        return $this->rol_id === Rol::PACIENTE; // 2
    }
    
    /**
     * Verificar si el usuario es médico
     */
    public function isMedico()
    {
        return $this->rol_id === Rol::MEDICO; // 3
    }
    
    /**
     * Verificar si el usuario está activo
     */
    public function isActive()
    {
        return $this->estado == 1;
    }
    
    // ========== ACCESORES ==========
    
    /**
     * Obtener el nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return $this->nombre;
    }
}