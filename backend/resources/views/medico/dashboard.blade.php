<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Panel Médico</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="medico-dash">

<div class="dashboard-layout">
    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </div>
            <div class="logo-text">
                <span class="logo-main">E&M</span>
                <span class="logo-sub">Laboratorio</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('medico.dashboard') }}" class="nav-item active">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('medico.citas') }}" class="nav-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Mis citas</span>
            </a>
            <a href="{{ route('medico.pacientes') }}" class="nav-item">
                <i class="fas fa-users"></i>
                <span>Mis pacientes</span>
            </a>
            <a href="{{ route('medico.diagnosticos') }}" class="nav-item">
                <i class="fas fa-file-alt"></i>
                <span>Diagnósticos</span>
            </a>
            <a href="{{ route('medico.historial') }}" class="nav-item">
                <i class="fas fa-history"></i>
                <span>Historial clínico</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar sesión</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="main-content">
        <div class="medico-header">
            <div class="medico-info">
                <div class="medico-avatar">{{ strtoupper(substr(($medico?->nombre ?: $usuario->nombre) ?? 'D', 0, 1)) }}</div>
                <div class="medico-datos">
                    <h1>Dr. {{ $medico?->nombreParaMostrar() ?: ($usuario->nombre ?? 'Médico') }}</h1>
                    <span class="medico-especialidad">{{ ($medico && $medico->especialidadParaMostrar() !== '') ? $medico->especialidadParaMostrar() : 'Medicina General' }}</span>
                </div>
            </div>
            <div class="medico-fecha">
                <i class="fas fa-calendar-alt"></i>
                <span>{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</span>
            </div>
        </div>

        {{-- TARJETAS DE ESTADÍSTICAS --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                <div>
                    <div class="stat-number">{{ $citasHoyCount ?? 0 }}</div>
                    <div class="stat-label">Citas hoy</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="stat-number">{{ $citasPendientesCount ?? 0 }}</div>
                    <div class="stat-label">Citas pendientes</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div>
                    <div class="stat-number">{{ $totalPacientes ?? 0 }}</div>
                    <div class="stat-label">Pacientes atendidos</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-file-medical"></i></div>
                <div>
                    <div class="stat-number">{{ $totalDiagnosticos ?? 0 }}</div>
                    <div class="stat-label">Diagnósticos</div>
                </div>
            </div>
        </div>

        {{-- SECCIÓN DE CITAS DE HOY --}}
        <div class="section-header">
            <h2><i class="fas fa-calendar-day"></i> Citas del día</h2>
        </div>

        <div class="citas-container">
            @if(isset($citasHoyList) && count($citasHoyList) > 0)
                @foreach($citasHoyList as $cita)
                <div class="cita-card-hoy">
                    <div class="cita-hora">
                        <div class="hora">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i') }}</div>
                    </div>
                    <div class="cita-info">
                        <div class="paciente-nombre">{{ $cita->paciente?->nombre ?? 'Paciente' }} {{ $cita->paciente?->apellido ?? '' }}</div>
                        <div class="cita-motivo">{{ $cita->motivo ?? 'Consulta general' }}</div>
                        <div class="cita-detalles">
                            <span><i class="fas fa-id-card"></i> {{ $cita->paciente?->DNI ?? 'N/A' }}</span>
                            <span><i class="fas fa-phone"></i> {{ $cita->paciente?->telefono ?? 'N/A' }}</span>
                            <span class="cita-estado-badge"><i class="fas fa-info-circle"></i> {{ ucfirst($cita->estado ?? 'pendiente') }}</span>
                        </div>
                    </div>
                    <div class="cita-acciones">
                        <button class="btn-atender" onclick="atenderCita({{ $cita->id }})">
                            <i class="fas fa-user-md"></i> Atender
                        </button>
                        <button class="btn-ver-paciente" onclick="verPaciente({{ $cita->paciente?->id ?? 0 }})">
                            <i class="fas fa-history"></i> Historial
                        </button>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-citas">
                    <i class="fas fa-calendar-day"></i>
                    <p>No hay citas programadas para hoy</p>
                    <span>Tu agenda está libre</span>
                </div>
            @endif
        </div>

        {{-- ACCIONES RÁPIDAS --}}
        <div class="section-header">
            <h2><i class="fas fa-bolt"></i> Acciones rápidas</h2>
        </div>
        <div class="acciones-grid">
            <a href="{{ route('medico.citas') }}" class="accion-card">
                <div class="accion-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="accion-info">
                    <div class="accion-title">Ver todas las citas</div>
                    <div class="accion-desc">Gestiona tus citas pendientes y futuras</div>
                </div>
                <div class="accion-arrow"><i class="fas fa-arrow-right"></i></div>
            </a>
            <a href="{{ route('medico.pacientes') }}" class="accion-card">
                <div class="accion-icon"><i class="fas fa-users"></i></div>
                <div class="accion-info">
                    <div class="accion-title">Mis pacientes</div>
                    <div class="accion-desc">Consulta el listado de tus pacientes</div>
                </div>
                <div class="accion-arrow"><i class="fas fa-arrow-right"></i></div>
            </a>
            <a href="{{ route('medico.diagnosticos') }}" class="accion-card">
                <div class="accion-icon"><i class="fas fa-file-alt"></i></div>
                <div class="accion-info">
                    <div class="accion-title">Registrar diagnóstico</div>
                    <div class="accion-desc">Agrega un nuevo diagnóstico a un paciente</div>
                </div>
                <div class="accion-arrow"><i class="fas fa-arrow-right"></i></div>
            </a>
        </div>
    </main>
</div>

<script>
    function atenderCita(citaId) {
        if (confirm('¿Iniciar atención de esta cita?')) {
            fetch(`/medico/citas/${citaId}/atender`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    alert('Cita iniciada correctamente');
                    location.reload();
                } else {
                    alert('Error al iniciar la cita');
                }
            });
        }
    }

    function verPaciente(pacienteId) {
        if (pacienteId) {
            window.location.href = `/medico/historial?paciente=${pacienteId}`;
        }
    }
</script>

<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body.medico-dash {
        background: #f0f4f8;
        font-family: 'Outfit', sans-serif;
    }

    .dashboard-layout {
        display: flex;
        min-height: 100vh;
    }

    /* SIDEBAR */
    .sidebar {
        width: 280px;
        background: linear-gradient(180deg, #1a2a3a 0%, #0f1a24 100%);
        color: white;
        display: flex;
        flex-direction: column;
        position: sticky;
        top: 0;
        height: 100vh;
    }

    .sidebar-logo { padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .logo { display: flex; align-items: center; gap: 12px; }
    .logo-icon { width: 40px; height: 40px; background: linear-gradient(135deg, #1a5fa8, #0d9e75); border-radius: 12px; display: flex; align-items: center; justify-content: center; }
    .logo-main { font-size: 1.2rem; font-weight: 700; font-family: 'Playfair Display', serif; color: white; }
    .logo-sub { font-size: 0.65rem; color: #0d9e75; text-transform: uppercase; }

    .sidebar-nav { flex: 1; padding: 1rem 0; }
    .nav-item { display: flex; align-items: center; gap: 12px; padding: 0.8rem 1.5rem; color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s; }
    .nav-item i { width: 22px; }
    .nav-item:hover { background: rgba(255,255,255,0.1); color: white; }
    .nav-item.active { background: rgba(13,158,117,0.2); border-left: 3px solid #0d9e75; color: white; }

    .sidebar-footer { padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.1); }
    .logout-btn { width: 100%; display: flex; align-items: center; gap: 12px; background: none; border: none; color: rgba(255,255,255,0.7); padding: 0.8rem; border-radius: 10px; cursor: pointer; }
    .logout-btn:hover { background: rgba(239,68,68,0.2); color: #ef4444; }

    /* MAIN CONTENT */
    .main-content { flex: 1; padding: 2rem; }
    .medico-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
    .medico-info { display: flex; align-items: center; gap: 1rem; }
    .medico-avatar { width: 60px; height: 60px; background: linear-gradient(135deg, #1a5fa8, #0d9e75); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; color: white; }
    .medico-datos h1 { font-size: 1.3rem; font-weight: 700; color: #1a2a3a; }
    .medico-especialidad { font-size: 0.7rem; color: #0d9e75; text-transform: uppercase; }
    .medico-fecha { background: white; padding: 0.5rem 1rem; border-radius: 30px; font-size: 0.8rem; color: #1a5fa8; }

    /* STATS */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
    .stat-card { background: white; border-radius: 16px; padding: 1rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .stat-icon { width: 45px; height: 45px; background: rgba(26,95,168,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #1a5fa8; }
    .stat-number { font-size: 1.3rem; font-weight: 800; color: #1a2a3a; }
    .stat-label { font-size: 0.65rem; color: #64748b; }

    /* CITAS DEL DÍA */
    .section-header { margin-bottom: 1rem; }
    .section-header h2 { font-size: 1rem; font-weight: 600; color: #1a2a3a; }
    .section-header i { color: #1a5fa8; margin-right: 8px; }

    .citas-container { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem; }
    .cita-card-hoy { background: white; border-radius: 16px; padding: 1rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: all 0.2s; }
    .cita-card-hoy:hover { transform: translateX(5px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .cita-hora { min-width: 80px; text-align: center; }
    .hora { font-size: 1.2rem; font-weight: 700; color: #1a5fa8; }
    .cita-info { flex: 1; }
    .paciente-nombre { font-weight: 700; font-size: 0.95rem; color: #1a2a3a; }
    .cita-motivo { font-size: 0.75rem; color: #64748b; margin-bottom: 4px; }
    .cita-detalles { display: flex; flex-wrap: wrap; gap: 0.75rem 1rem; font-size: 0.7rem; color: #94a3b8; }
    .cita-estado-badge { color: #1a5fa8; font-weight: 600; }
    .cita-acciones { display: flex; gap: 0.5rem; }
    .btn-atender { background: linear-gradient(135deg, #1a5fa8, #0d9e75); border: none; padding: 0.5rem 1rem; border-radius: 8px; color: white; cursor: pointer; font-size: 0.75rem; font-weight: 600; }
    .btn-ver-paciente { background: #f1f5f9; border: none; padding: 0.5rem 1rem; border-radius: 8px; color: #1a5fa8; cursor: pointer; font-size: 0.75rem; font-weight: 600; }

    /* ACCIONES RÁPIDAS */
    .acciones-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
    .accion-card { background: white; border-radius: 16px; padding: 1rem; display: flex; align-items: center; gap: 1rem; text-decoration: none; transition: all 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .accion-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(26,95,168,0.1); }
    .accion-icon { width: 50px; height: 50px; background: rgba(13,158,117,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #0d9e75; }
    .accion-info { flex: 1; }
    .accion-title { font-size: 0.85rem; font-weight: 700; color: #1a2a3a; }
    .accion-desc { font-size: 0.65rem; color: #64748b; }
    .accion-arrow { color: #cbd5e1; }

    .empty-citas { text-align: center; padding: 2rem; background: white; border-radius: 16px; }
    .empty-citas i { font-size: 2rem; color: #cbd5e1; margin-bottom: 0.5rem; }

    @media (max-width: 900px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .acciones-grid { grid-template-columns: 1fr; }
        .sidebar { width: 70px; }
        .sidebar .logo-text, .sidebar .nav-item span, .sidebar .logout-btn span { display: none; }
        .main-content { padding: 1rem; }
    }
</style>

</body>
</html>