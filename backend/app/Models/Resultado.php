<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resultado extends Model
{
    protected $table = 'resultados';

    public $timestamps = false;

    protected $fillable = [
        'paciente_id',
        'nombre',
        'descripcion',
        'tipo',
        'especialista',
        'servicio',
        'fecha_resultado',
        'estado',
        'detalle',
    ];

    protected $casts = [
        'fecha_resultado' => 'datetime',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function getFechaResultadoFormattedAttribute(): string
    {
        if (!$this->fecha_resultado) {
            return '';
        }

        return $this->fecha_resultado->locale('es')->translatedFormat('d M Y');
    }

    /**
     * Compatibilidad con vistas que usan `nombre_examen` o `resultado` (historial médico).
     */
    public function getNombreExamenAttribute(): string
    {
        return $this->attributes['nombre'] ?? '';
    }

    public function getResultadoAttribute(): ?string
    {
        return $this->attributes['detalle'] ?? null;
    }
}
