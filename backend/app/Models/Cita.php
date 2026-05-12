<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Cita extends Model
{
    protected $table = 'citas';
    public $timestamps = false;

    protected $fillable = [
        'paciente_id',
        'medico_id',
        'fecha_hora',
        'estado',
        'motivo',
        'tipo',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
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

    /**
     * Citas donde soy el médico de la cita o el médico asignado al paciente (p. ej. cita desde landing sin médico).
     *
     * Incluye la cola común: `medico_id` nulo y paciente sin `medico_asignado_id` (reservas web), visibles para cualquier médico hasta que alguien atienda.
     */
    public function scopeParaMedico($query, int $medicoId)
    {
        return $query->where(function ($q) use ($medicoId) {
            $q->where('medico_id', $medicoId);

            if (Schema::hasColumn('pacientes', 'medico_asignado_id')) {
                $q->orWhereHas('paciente', function ($pq) use ($medicoId) {
                    $pq->where('medico_asignado_id', $medicoId);
                });
            }

            $q->orWhere(function ($sub) {
                $sub->whereNull('medico_id');
                if (Schema::hasColumn('pacientes', 'medico_asignado_id')) {
                    $sub->whereHas('paciente', function ($pq) {
                        $pq->whereNull('medico_asignado_id');
                    });
                }
            });
        });
    }
}