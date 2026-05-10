<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Historial Clínico</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('css/historial.css') }}">
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
            <a href="{{ route('medico.dashboard') }}" class="nav-item">
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
            <a href="{{ route('medico.historial') }}" class="nav-item active">
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
        <div class="page-header">
            <h1><i class="fas fa-history"></i> Historial clínico</h1>
            <p>Consulta el historial completo de tus pacientes</p>
        </div>

        {{-- SELECTOR DE PACIENTE MEJORADO --}}
        <div class="selector-paciente-historial">
            <div class="filtros-paciente">
                <div class="filtros-left">
                    <div class="select-group">
                        <label><i class="fas fa-user-md"></i> Seleccionar paciente:</label>
                        <select id="selectPaciente" class="filtro-select">
                            <option value="">-- Seleccione un paciente --</option>
                            @foreach($pacientes ?? [] as $paciente)
                                <option value="{{ $paciente->id }}" {{ request('paciente') == $paciente->id ? 'selected' : '' }}>
                                    {{ $paciente->nombre }} {{ $paciente->apellido ?? '' }} - {{ $paciente->DNI ?? 'Sin DNI' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        @if($pacienteSeleccionado)
        {{-- INFORMACIÓN DEL PACIENTE --}}
        <div class="info-paciente-historial">
            <div class="paciente-header">
                <div class="paciente-avatar-grande">
                    <div class="avatar-inicial">{{ strtoupper(substr($pacienteSeleccionado->nombre, 0, 1)) }}{{ strtoupper(substr($pacienteSeleccionado->apellido ?? '', 0, 1)) }}</div>
                </div>
                <div class="paciente-info-principal">
                    <h2>{{ $pacienteSeleccionado->nombre }} {{ $pacienteSeleccionado->apellido ?? '' }}</h2>
                    <div class="info-detalles">
                        <span><i class="fas fa-id-card"></i> DNI: {{ $pacienteSeleccionado->DNI ?? 'N/A' }}</span>
                        <span><i class="fas fa-calendar-alt"></i> Edad: {{ $pacienteSeleccionado->edad ?? '?' }} años</span>
                        <span><i class="fas fa-venus-mars"></i> Género: {{ $pacienteSeleccionado->genero == 'M' ? 'Masculino' : 'Femenino' }}</span>
                        <span><i class="fas fa-phone"></i> Teléfono: {{ $pacienteSeleccionado->telefono ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABS PARA NAVEGAR --}}
        <div class="tabs-historial">
            <button class="tab-btn active" data-tab="diagnosticos">
                <i class="fas fa-file-alt"></i> Diagnósticos
            </button>
            <button class="tab-btn" data-tab="recetas">
                <i class="fas fa-prescription-bottle"></i> Recetas
            </button>
            <button class="tab-btn" data-tab="resultados">
                <i class="fas fa-chart-line"></i> Resultados
            </button>
            <button class="tab-btn" data-tab="citas">
                <i class="fas fa-calendar-alt"></i> Citas
            </button>
        </div>

        {{-- CONTENIDO DE DIAGNÓSTICOS --}}
        <div class="tab-content active" id="tab-diagnosticos">
            <div class="historial-card">
                <div class="card-header">
                    <h3><i class="fas fa-stethoscope"></i> Historial de diagnósticos</h3>
                    <button class="btn-nuevo" onclick="nuevoDiagnostico({{ $pacienteSeleccionado->id }})">
                        <i class="fas fa-plus"></i> Nuevo diagnóstico
                    </button>
                </div>
                @if(isset($diagnosticos) && count($diagnosticos) > 0)
                    <div class="lista-historial">
                        @foreach($diagnosticos as $diagnostico)
                        <div class="historial-item">
                            <div class="item-fecha">{{ \Carbon\Carbon::parse($diagnostico->created_at)->format('d/m/Y') }}</div>
                            <div class="item-contenido">
                                <div class="item-titulo">{{ $diagnostico->nombre }}</div>
                                <div class="item-descripcion">{{ $diagnostico->descripcion }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-historial">
                        <i class="fas fa-stethoscope"></i>
                        <p>No hay diagnósticos registrados</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- CONTENIDO DE RECETAS --}}
        <div class="tab-content" id="tab-recetas">
            <div class="historial-card">
                <div class="card-header">
                    <h3><i class="fas fa-prescription-bottle"></i> Historial de recetas</h3>
                    <button class="btn-nuevo" onclick="nuevaReceta({{ $pacienteSeleccionado->id }})">
                        <i class="fas fa-plus"></i> Nueva receta
                    </button>
                </div>
                @if(isset($recetas) && count($recetas) > 0)
                    <div class="lista-historial">
                        @foreach($recetas as $receta)
                        <div class="historial-item">
                            <div class="item-fecha">{{ \Carbon\Carbon::parse($receta->created_at)->format('d/m/Y') }}</div>
                            <div class="item-contenido">
                                <div class="item-titulo">{{ $receta->medicamento }}</div>
                                <div class="item-descripcion">{{ $receta->indicaciones }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-historial">
                        <i class="fas fa-prescription-bottle"></i>
                        <p>No hay recetas registradas</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- CONTENIDO DE RESULTADOS --}}
        <div class="tab-content" id="tab-resultados">
            <div class="historial-card">
                <div class="card-header">
                    <h3><i class="fas fa-chart-line"></i> Historial de resultados</h3>
                </div>
                @if(isset($resultados) && count($resultados) > 0)
                    <div class="lista-historial">
                        @foreach($resultados as $resultado)
                        <div class="historial-item">
                            <div class="item-fecha">{{ \Carbon\Carbon::parse($resultado->fecha_resultado)->format('d/m/Y') }}</div>
                            <div class="item-contenido">
                                <div class="item-titulo">{{ $resultado->nombre_examen }}</div>
                                <div class="item-descripcion">{{ $resultado->resultado }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-historial">
                        <i class="fas fa-chart-line"></i>
                        <p>No hay resultados registrados</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- CONTENIDO DE CITAS --}}
        <div class="tab-content" id="tab-citas">
            <div class="historial-card">
                <div class="card-header">
                    <h3><i class="fas fa-calendar-alt"></i> Historial de citas</h3>
                    <button class="btn-nuevo" onclick="agendarCita({{ $pacienteSeleccionado->id }})">
                        <i class="fas fa-plus"></i> Nueva cita
                    </button>
                </div>
                @if(isset($citas) && count($citas) > 0)
                    <div class="lista-historial">
                        @foreach($citas as $cita)
                        <div class="historial-item">
                            <div class="item-fecha">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y H:i') }}</div>
                            <div class="item-contenido">
                                <div class="item-titulo">{{ $cita->tipo ?? 'Consulta general' }}</div>
                                <div class="item-descripcion">{{ $cita->motivo ?? 'Sin motivo especificado' }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-historial">
                        <i class="fas fa-calendar-alt"></i>
                        <p>No hay citas registradas</p>
                    </div>
                @endif
            </div>
        </div>
        @else
        <div class="empty-seleccion">
            <i class="fas fa-user-search"></i>
            <p>Selecciona un paciente para ver su historial clínico completo</p>
        </div>
        @endif
    </main>
</div>

<script>
    // ========== CAMBIAR PACIENTE ==========
    document.getElementById('selectPaciente')?.addEventListener('change', function() {
        if (this.value) {
            window.location.href = `/medico/historial?paciente=${this.value}`;
        }
    });
    
    // ========== TABS ==========
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            document.getElementById(`tab-${tabId}`).classList.add('active');
        });
    });
    
    // ========== FUNCIONES DE NAVEGACIÓN ==========
    function nuevoDiagnostico(pacienteId) {
        window.location.href = `/medico/diagnosticos/crear?paciente=${pacienteId}`;
    }
    
    function nuevaReceta(pacienteId) {
        window.location.href = `/medico/recetas/crear?paciente=${pacienteId}`;
    }
    
    function agendarCita(pacienteId) {
        window.location.href = `/medico/citas/agendar?paciente=${pacienteId}`;
    }
</script>

<style>
    /* Estilos para filtros */
    .filtros-paciente {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        background: white;
        padding: 1rem;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    
    .filtros-left {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 1rem;
    }
    
    .select-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #f8fafc;
        padding: 0.3rem 0.8rem;
        border-radius: 30px;
    }
    
    .select-group label {
        font-size: 0.7rem;
        font-weight: 600;
        color: #1a2a3a;
    }
    
    .select-group label i {
        color: #1a5fa8;
        margin-right: 4px;
    }
    
    .filtro-select {
        padding: 0.4rem 2rem 0.4rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        background: white;
        font-size: 0.75rem;
        font-family: 'Outfit', sans-serif;
        cursor: pointer;
        outline: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
    }
    
    .filtro-select:hover {
        border-color: #1a5fa8;
    }
    
    .filtro-select:focus {
        border-color: #1a5fa8;
        box-shadow: 0 0 0 2px rgba(26, 95, 168, 0.1);
    }
    
    .selector-paciente-historial {
        margin-bottom: 1.5rem;
    }
    
    .info-paciente-historial {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .paciente-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    
    .paciente-avatar-grande {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #1a5fa8, #0d9e75);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .paciente-avatar-grande .avatar-inicial {
        font-size: 2rem;
        font-weight: 700;
        color: white;
    }
    
    .paciente-info-principal h2 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1a2a3a;
        margin-bottom: 0.5rem;
    }
    
    .info-detalles {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .info-detalles span {
        font-size: 0.8rem;
        color: #64748b;
    }
    
    .info-detalles i {
        margin-right: 5px;
        color: #1a5fa8;
    }
    
    .tabs-historial {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }
    
    .tab-btn {
        padding: 0.5rem 1.2rem;
        border: none;
        background: #f1f5f9;
        border-radius: 30px;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .tab-btn.active {
        background: #1a5fa8;
        color: white;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .historial-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .btn-nuevo {
        background: #1a5fa8;
        border: none;
        padding: 0.3rem 1rem;
        border-radius: 20px;
        color: white;
        font-size: 0.7rem;
        cursor: pointer;
    }
    
    .lista-historial {
        padding: 0;
    }
    
    .historial-item {
        display: flex;
        padding: 1rem;
        border-bottom: 1px solid #f0f2f8;
    }
    
    .item-fecha {
        min-width: 90px;
        font-size: 0.7rem;
        color: #64748b;
    }
    
    .item-contenido {
        flex: 1;
    }
    
    .item-titulo {
        font-weight: 700;
        color: #1a2a3a;
        margin-bottom: 4px;
        font-size: 0.85rem;
    }
    
    .item-descripcion {
        font-size: 0.75rem;
        color: #64748b;
    }
    
    .empty-historial, .empty-seleccion {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 20px;
    }
    
    .empty-historial i, .empty-seleccion i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }
    
    @media (max-width: 768px) {
        .historial-item {
            flex-direction: column;
            gap: 0.5rem;
        }
        .info-detalles {
            flex-direction: column;
            gap: 0.3rem;
        }
        .filtros-paciente {
            flex-direction: column;
        }
        .filtros-left {
            flex-direction: column;
            width: 100%;
        }
        .select-group {
            justify-content: space-between;
            width: 100%;
        }
        .filtro-select {
            flex: 1;
        }
    }
</style>

</body>
</html>