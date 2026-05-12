<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!view()->exists('admin.dashboard')) {
            return redirect()->route('home');
        }

        $totalPacientes = Schema::hasTable('pacientes') ? Paciente::count() : 0;
        $totalMedicos = Schema::hasTable('medicos') ? Medico::count() : 0;
        $totalCitas = Schema::hasTable('citas') ? Cita::count() : 0;

        $totalUsuarios = 0;
        if (Schema::hasTable('usuarios')) {
            $totalUsuarios = Usuario::query()
                ->where(function ($q) {
                    $q->where('estado', 'activo')
                        ->orWhere('estado', '1')
                        ->orWhere('estado', 1);
                })
                ->count();
        }

        $citasPendientes = 0;
        $citasConfirmadas = 0;
        $citasCompletadas = 0;
        $citasCanceladas = 0;

        if (Schema::hasTable('citas')) {
            $pend = ['pendiente', 'pendientes', 'programada', 'programado', 'agendada', 'agendado'];
            $conf = ['confirmada', 'confirmado', 'aprobada', 'aprobado'];
            $comp = ['completada', 'completado', 'atendida', 'atendido', 'finalizada', 'finalizado'];
            $canc = ['cancelada', 'cancelado', 'anulada', 'anulado'];

            $citasPendientes = (int) Cita::whereRaw('LOWER(TRIM(estado)) IN (' . implode(',', array_fill(0, count($pend), '?')) . ')', $pend)->count();
            $citasConfirmadas = (int) Cita::whereRaw('LOWER(TRIM(estado)) IN (' . implode(',', array_fill(0, count($conf), '?')) . ')', $conf)->count();
            $citasCompletadas = (int) Cita::whereRaw('LOWER(TRIM(estado)) IN (' . implode(',', array_fill(0, count($comp), '?')) . ')', $comp)->count();
            $citasCanceladas = (int) Cita::whereRaw('LOWER(TRIM(estado)) IN (' . implode(',', array_fill(0, count($canc), '?')) . ')', $canc)->count();
        }

        $citasPorDia = collect();
        if (Schema::hasTable('citas')) {
            for ($i = 13; $i >= 0; $i--) {
                $d = now()->copy()->subDays($i)->startOfDay();
                $totalDia = Cita::query()
                    ->whereDate('fecha_hora', $d->toDateString())
                    ->count();
                $citasPorDia->push([
                    'fecha' => $d->format('d/m'),
                    'total' => $totalDia,
                ]);
            }
        }

        $ultimosPacientes = collect();
        if (Schema::hasTable('pacientes')) {
            $q = Paciente::query()->orderByDesc('id')->limit(5);
            $with = ['usuario'];
            if (Schema::hasColumn('pacientes', 'medico_asignado_id')) {
                $with[] = 'medicoAsignado';
            }
            $q->with($with);
            $ultimosPacientes = $q->get();
        }

        return view('admin.dashboard', [
            'usuario' => Auth::user(),
            'totalPacientes' => $totalPacientes,
            'totalMedicos' => $totalMedicos,
            'totalCitas' => $totalCitas,
            'totalUsuarios' => $totalUsuarios,
            'citasPendientes' => $citasPendientes,
            'citasConfirmadas' => $citasConfirmadas,
            'citasCompletadas' => $citasCompletadas,
            'citasCanceladas' => $citasCanceladas,
            'citasPorDia' => $citasPorDia,
            'ultimosPacientes' => $ultimosPacientes,
        ]);
    }
}
