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

    /**
     * ID en `pacientes` para esta cuenta: usa `usuarios.paciente_id` o, si falta, busca por `pacientes.usuario_id`.
     */
    public function resolvePacienteId(): ?int
    {
        if ($this->paciente_id) {
            return (int) $this->paciente_id;
        }

        $id = Paciente::query()->where('usuario_id', $this->id)->value('id');

        return $id !== null ? (int) $id : null;
    }

    /**
     * `usuarios.estado` puede ser entero, "1"/"0" o texto ("activo"/"inactivo").
     */
    public function isEstadoActivo(): bool
    {
        $e = $this->estado;

        if ($e === true || $e === 1) {
            return true;
        }

        if ($e === false || $e === 0) {
            return false;
        }

        if (is_string($e)) {
            $t = strtolower(trim($e));

            return in_array($t, ['1', 'true', 'activo', 'active', 'si', 'sí', 'enabled'], true);
        }

        return false;
    }
}