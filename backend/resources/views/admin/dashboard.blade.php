<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Panel Administrador</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('css/admin.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-dash">

<div class="admin-layout">
    @include('admin.partials.sidebar')

    {{-- MAIN CONTENT --}}
    <main class="admin-main">
        <div class="admin-header">
            <h1><i class="fas fa-chart-line"></i> Dashboard</h1>
            <div class="admin-user">
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->nombre }}</div>
                    <div class="user-role">Administrador</div>
                </div>
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}</div>
            </div>
        </div>

        {{-- TARJETAS DE ESTADÍSTICAS --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $totalPacientes ?? 0 }}</div>
                    <div class="stat-label">Pacientes totales</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-user-md"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $totalMedicos ?? 0 }}</div>
                    <div class="stat-label">Médicos</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon amber"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $totalCitas ?? 0 }}</div>
                    <div class="stat-label">Citas totales</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $totalUsuarios ?? 0 }}</div>
                    <div class="stat-label">Usuarios activos</div>
                </div>
            </div>
        </div>

        {{-- GRÁFICOS --}}
        <div class="charts-row">
            <div class="chart-card">
                <div class="chart-header"><h3><i class="fas fa-chart-pie"></i> Citas por estado</h3></div>
                <div class="chart-canvas-wrap">
                    <canvas id="citasEstadoChart" height="220"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <div class="chart-header"><h3><i class="fas fa-chart-line"></i> Citas por día</h3></div>
                <div class="chart-canvas-wrap">
                    <canvas id="citasPorDiaChart" height="220"></canvas>
                </div>
            </div>
        </div>

        {{-- ÚLTIMOS PACIENTES --}}
        <div class="data-panel">
            <div class="panel-header">
                <h3><i class="fas fa-users"></i> Últimos pacientes registrados</h3>
                <a href="{{ route('admin.pacientes.index') }}" class="btn-ver-todos">Ver todos →</a>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>DNI</th>
                            <th>Médico asignado</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimosPacientes ?? [] as $paciente)
                        <tr>
                            <td>{{ $paciente->nombre }} {{ $paciente->apellido }}</td>
                            <td>{{ $paciente->DNI ?? 'N/A' }}</td>
                            <td>{{ $paciente->medicoAsignado?->nombre ?? 'No asignado' }}</td>
                            <td>
                                @if($paciente->usuario)
                                    <span class="badge {{ ($paciente->usuario->estado == 1 || $paciente->usuario->estado === 'activo') ? 'active' : 'inactive' }}">
                                        {{ ($paciente->usuario->estado == 1 || $paciente->usuario->estado === 'activo') ? 'Activo' : 'Inactivo' }}
                                    </span>
                                @else
                                    <span class="badge sin-asignar">Sin cuenta</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-row">No hay pacientes</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
    const chartCommon = { responsive: true, maintainAspectRatio: false };

    new Chart(document.getElementById('citasEstadoChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pendientes', 'Confirmadas', 'Completadas', 'Canceladas'],
            datasets: [{
                data: [{{ (int)($citasPendientes ?? 0) }}, {{ (int)($citasConfirmadas ?? 0) }}, {{ (int)($citasCompletadas ?? 0) }}, {{ (int)($citasCanceladas ?? 0) }}],
                backgroundColor: ['#f59e0b', '#1a5fa8', '#0d9e75', '#ef4444'],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 6
            }]
        },
        options: {
            ...chartCommon,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 14 } }
            }
        }
    });

    new Chart(document.getElementById('citasPorDiaChart'), {
        type: 'line',
        data: {
            labels: [@foreach($citasPorDia ?? [] as $dia)'{{ $dia['fecha'] }}', @endforeach],
            datasets: [{
                label: 'Citas',
                data: [@foreach($citasPorDia ?? [] as $dia){{ (int) $dia['total'] }}, @endforeach],
                borderColor: '#1a5fa8',
                backgroundColor: 'rgba(26, 95, 168, 0.12)',
                fill: true,
                tension: 0.25,
                pointRadius: 3,
                pointHoverRadius: 5
            }]
        },
        options: {
            ...chartCommon,
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMin: 0,
                    ticks: { precision: 0, stepSize: 1 }
                },
                x: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });
</script>

<style>
    .chart-canvas-wrap { position: relative; height: 260px; max-height: 40vh; }

    /* ============================================================
       RESPONSIVE - DASHBOARD ADMIN
       ============================================================ */

    /* Tablets (max-width: 1024px) */
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
        
        .charts-row {
            grid-template-columns: 1fr !important;
        }
    }

    /* Móviles (max-width: 768px) */
    @media (max-width: 768px) {
        .admin-layout {
            flex-direction: column;
        }
        
        .admin-sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }
        
        .sidebar-nav {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 5px;
        }
        
        .nav-item {
            padding: 0.5rem 1rem;
            border-radius: 30px;
        }
        
        .nav-item.active {
            border-left: none;
            border-bottom: 2px solid #0d9e75;
        }
        
        .admin-header {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr !important;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .data-table {
            min-width: 500px;
        }
    }

    /* Móviles pequeños (max-width: 480px) */
    @media (max-width: 480px) {
        .admin-main {
            padding: 1rem;
        }
        
        .stat-number {
            font-size: 1.2rem;
        }
        
        .stat-label {
            font-size: 0.6rem;
        }
        
        .acciones-cell button {
            width: 28px;
            height: 28px;
        }
        
        .btn-primary {
            padding: 0.4rem 0.8rem;
            font-size: 0.7rem;
        }
        
        .search-box input {
            width: 150px;
        }
    }
</style>

</body>
</html>