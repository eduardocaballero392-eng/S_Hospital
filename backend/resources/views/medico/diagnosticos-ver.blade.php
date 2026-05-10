<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Ver Diagnóstico</title>
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
            <a href="{{ route('medico.dashboard') }}" class="nav-item">Dashboard</a>
            <a href="{{ route('medico.citas') }}" class="nav-item">Mis citas</a>
            <a href="{{ route('medico.pacientes') }}" class="nav-item">Mis pacientes</a>
            <a href="{{ route('medico.recetas') }}" class="nav-item">Recetas</a>
            <a href="{{ route('medico.diagnosticos') }}" class="nav-item active">Diagnósticos</a>
            <a href="{{ route('medico.historial') }}" class="nav-item">Historial clínico</a>
            <a href="{{ route('medico.perfil') }}" class="nav-item">Mi perfil</a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Cerrar sesión</button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-file-alt"></i> Detalle del diagnóstico</h1>
            <p>Información completa del diagnóstico registrado</p>
        </div>

        <div class="detalle-container">
            <div class="detalle-card">
                <div class="detalle-header">
                    <div class="detalle-titulo">{{ $diagnostico->nombre }}</div>
                    <div class="detalle-fecha">{{ \Carbon\Carbon::parse($diagnostico->fecha_diagnostico)->format('d/m/Y') }}</div>
                </div>

                <div class="detalle-info">
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-user"></i> Paciente:</div>
                        <div class="info-value">{{ $diagnostico->paciente->nombre }} {{ $diagnostico->paciente->apellido ?? '' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-id-card"></i> DNI:</div>
                        <div class="info-value">{{ $diagnostico->paciente->DNI ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-stethoscope"></i> Diagnóstico:</div>
                        <div class="info-value">{{ $diagnostico->nombre }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-calendar-alt"></i> Fecha:</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($diagnostico->fecha_diagnostico)->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-align-left"></i> Descripción:</div>
                        <div class="info-value descripcion">{{ $diagnostico->descripcion }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-clock"></i> Registrado:</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($diagnostico->created_at)->format('d/m/Y H:i') }}</div>
                    </div>
                </div>

                <div class="detalle-buttons">
                    <a href="{{ route('medico.diagnosticos') }}" class="btn-volver">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <a href="{{ route('medico.diagnosticos.edit', $diagnostico->id) }}" class="btn-editar-detalle">
                        <i class="fas fa-edit"></i> Editar diagnóstico
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
    .detalle-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .detalle-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    
    .detalle-header {
        background: linear-gradient(135deg, #1a5fa8, #0d9e75);
        padding: 1.5rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .detalle-titulo {
        font-size: 1.2rem;
        font-weight: 700;
    }
    
    .detalle-fecha {
        font-size: 0.8rem;
        background: rgba(255,255,255,0.2);
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
    }
    
    .detalle-info {
        padding: 1.5rem;
    }
    
    .info-row {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid #f0f2f8;
    }
    
    .info-label {
        width: 120px;
        font-weight: 600;
        color: #1a2a3a;
        font-size: 0.85rem;
    }
    
    .info-label i {
        color: #1a5fa8;
        width: 20px;
        margin-right: 8px;
    }
    
    .info-value {
        flex: 1;
        color: #64748b;
        font-size: 0.85rem;
    }
    
    .info-value.descripcion {
        line-height: 1.6;
        white-space: pre-wrap;
    }
    
    .detalle-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding: 1rem 1.5rem 1.5rem;
    }
    
    .btn-volver {
        background: #f1f5f9;
        color: #64748b;
        padding: 0.6rem 1.2rem;
        border-radius: 10px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    
    .btn-volver:hover {
        background: #e2e8f0;
    }
    
    .btn-editar-detalle {
        background: linear-gradient(135deg, #1a5fa8, #0d9e75);
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 10px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    
    .btn-editar-detalle:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26, 95, 168, 0.3);
    }
    
    @media (max-width: 600px) {
        .info-row {
            flex-direction: column;
            gap: 0.3rem;
        }
        .info-label {
            width: 100%;
        }
        .detalle-buttons {
            flex-direction: column;
        }
        .btn-volver, .btn-editar-detalle {
            text-align: center;
        }
    }
</style>

</body>
</html>