<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Mis Pacientes</title>
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
            <a href="{{ route('medico.dashboard') }}" class="nav-item">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('medico.citas') }}" class="nav-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Mis citas</span>
            </a>
            <a href="{{ route('medico.pacientes') }}" class="nav-item active">
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
        <div class="page-header">
            <h1><i class="fas fa-users"></i> Mis pacientes</h1>
            <p>Gestiona toda la información de tus pacientes y accede a su historial clínico</p>
        </div>

        @if (session('warning'))
            <div class="alert alert-warning" role="alert" style="margin: 0 0 1.25rem; padding: 0.85rem 1rem; background: #fff8e6; border: 1px solid #e6c200; border-radius: 8px; color: #5c4a00;">
                {{ session('warning') }}
            </div>
        @endif

        {{-- ESTADÍSTICAS --}}
        <div class="stats-pacientes">
            <div class="stat-card-paciente">
                <div class="stat-icon"><i class="fas fa-user-friends"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $totalPacientes ?? 0 }}</div>
                    <div class="stat-label">Pacientes totales</div>
                </div>
            </div>
            <div class="stat-card-paciente">
                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $pacientesActivos ?? 0 }}</div>
                    <div class="stat-label">Activos este mes</div>
                </div>
            </div>
            <div class="stat-card-paciente">
                <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $nuevosPacientes ?? 0 }}</div>
                    <div class="stat-label">Nuevos pacientes</div>
                </div>
            </div>
            <div class="stat-card-paciente">
                <div class="stat-icon"><i class="fas fa-prescription"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $recetasEmitidas ?? 0 }}</div>
                    <div class="stat-label">Recetas emitidas</div>
                </div>
            </div>
        </div>

        {{-- FILTROS MEJORADOS (estilo igual a citas) --}}
<div class="filtros-paciente">
    <div class="filtros-left">
        {{-- SELECT PARA GRUPO EDAD --}}
        <div class="select-group">
            <label><i class="fas fa-calendar-alt"></i> Edad:</label>
            <select id="filtroGrupoEdad" class="filtro-select">
                <option value="todos">Todas las edades</option>
                <option value="menor12">Menores de 12 años</option>
                <option value="12a18">12 - 18 años</option>
                <option value="19a40">19 - 40 años</option>
                <option value="41a60">41 - 60 años</option>
                <option value="mayor60">Mayores de 60 años</option>
            </select>
        </div>

        {{-- SELECT PARA GÉNERO --}}
        <div class="select-group">
            <label><i class="fas fa-venus-mars"></i> Género:</label>
            <select id="filtroGenero" class="filtro-select">
                <option value="todos">Todos los géneros</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
            </select>
        </div>
    </div>

    <div class="buscador-paciente">
        <i class="fas fa-search"></i>
        <input type="text" id="buscadorPacientes" placeholder="Buscar por nombre, DNI o teléfono...">
        <button id="limpiarFiltros" class="btn-limpiar">
            <i class="fas fa-eraser"></i> Limpiar
        </button>
    </div>
</div>

        {{-- TABLA DE PACIENTES --}}
        <div class="tabla-pacientes-container">
            <table class="tabla-pacientes">
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>DNI</th>
                        <th>Edad</th>
                        <th>Género</th>
                        <th>Teléfono</th>
                        <th>Última cita</th>
                        <th>Próxima cita</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaPacientesBody">
                    @if(isset($pacientes) && count($pacientes) > 0)
                        @foreach($pacientes as $paciente)
                        <tr data-nombre="{{ strtolower($paciente->nombre) }} {{ strtolower($paciente->apellido ?? '') }}"
                            data-dni="{{ $paciente->DNI ?? '' }}"
                            data-telefono="{{ $paciente->telefono ?? '' }}"
                            data-edad="{{ $paciente->edad ?? 0 }}"
                            data-genero="{{ $paciente->genero ?? '' }}">
                            <td>
                                <div class="paciente-avatar">
                                    <div class="avatar-inicial">{{ strtoupper(substr($paciente->nombre, 0, 1)) }}{{ strtoupper(substr($paciente->apellido ?? '', 0, 1)) }}</div>
                                    <div class="paciente-info-cell">
                                        <div class="paciente-nombre-cell">{{ $paciente->nombre }} {{ $paciente->apellido ?? '' }}</div>
                                        <div class="paciente-ultimo-diagnostico">{{ $paciente->ultimo_diagnostico ?? 'Sin diagnóstico reciente' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $paciente->DNI ?? 'N/A' }}</td>
                            <td>{{ $paciente->edad ?? '?' }} años</td>
                            <td>
                                <span class="genero-badge {{ $paciente->genero == 'M' ? 'masculino' : 'femenino' }}">
                                    {{ $paciente->genero == 'M' ? 'Masculino' : 'Femenino' }}
                                </span>
                            </td>
                            <td>{{ $paciente->telefono ?? 'N/A' }}</td>
                            <td>
                                @if($paciente->ultima_cita)
                                    <span class="fecha-cell">{{ \Carbon\Carbon::parse($paciente->ultima_cita)->format('d/m/Y') }}</span>
                                @else
                                    <span class="sin-cita">Sin citas</span>
                                @endif
                            </td>
                            <td>
                                @if($paciente->proxima_cita)
                                    <span class="fecha-cell proxima">{{ \Carbon\Carbon::parse($paciente->proxima_cita)->format('d/m/Y') }}</span>
                                @else
                                    <span class="sin-cita">No programada</span>
                                @endif
                            </td>
                            <td class="acciones-cell">
                                <button class="btn-ver-historial" onclick="verHistorialPaciente({{ $paciente->id }})" title="Ver historial clínico">
                                    <i class="fas fa-history"></i>
                                </button>
                                <button class="btn-agendar-cita" onclick="agendarCita({{ $paciente->id }})" title="Agendar cita">
                                    <i class="fas fa-calendar-plus"></i>
                                </button>
                                <button class="btn-receta" onclick="crearReceta({{ $paciente->id }})" title="Crear receta">
                                    <i class="fas fa-prescription-bottle"></i>
                                </button>
                                <button class="btn-diagnostico" onclick="crearDiagnostico({{ $paciente->id }})" title="Registrar diagnóstico">
                                    <i class="fas fa-file-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr class="empty-row">
                            <td colspan="8">
                                <div class="empty-pacientes">
                                    <i class="fas fa-user-friends"></i>
                                    <p>No tienes pacientes asignados</p>
                                    <span>Los pacientes que te consulten aparecerán aquí</span>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </main>
</div>

{{-- MODAL PARA VER PACIENTE RÁPIDO --}}
<div class="modal-overlay" id="modalPaciente">
    <div class="modal-container modal-large">
        <div class="modal-header">
            <h3><i class="fas fa-user-circle"></i> Información del paciente</h3>
            <button class="modal-close" onclick="cerrarModal()">&times;</button>
        </div>
        <div class="modal-body" id="modalPacienteBody">
            <!-- Contenido dinámico -->
        </div>
    </div>
</div>

<script>
    // Filtrar pacientes
    function filtrarPacientes() {
        const searchTerm = document.getElementById('buscadorPacientes').value.toLowerCase();
        const grupoEdad = document.getElementById('filtroGrupoEdad').value;
        const genero = document.getElementById('filtroGenero').value;
        
        const rows = document.querySelectorAll('#tablaPacientesBody tr');
        
        rows.forEach(row => {
            if (row.classList.contains('empty-row')) return;
            
            const nombre = row.getAttribute('data-nombre') || '';
            const dni = row.getAttribute('data-dni') || '';
            const telefono = row.getAttribute('data-telefono') || '';
            const edad = parseInt(row.getAttribute('data-edad') || 0);
            const rowGenero = row.getAttribute('data-genero') || '';
            
            let mostrar = true;
            
            // Búsqueda
            if (searchTerm !== '') {
                const coincide = nombre.includes(searchTerm) || dni.includes(searchTerm) || telefono.includes(searchTerm);
                if (!coincide) mostrar = false;
            }
            
            // Filtro por grupo de edad
            if (mostrar && grupoEdad !== 'todos') {
                switch(grupoEdad) {
                    case 'menor12':
                        if (edad >= 12) mostrar = false;
                        break;
                    case '12a18':
                        if (edad < 12 || edad > 18) mostrar = false;
                        break;
                    case '19a40':
                        if (edad < 19 || edad > 40) mostrar = false;
                        break;
                    case '41a60':
                        if (edad < 41 || edad > 60) mostrar = false;
                        break;
                    case 'mayor60':
                        if (edad <= 60) mostrar = false;
                        break;
                }
            }
            
            // Filtro por género
            if (mostrar && genero !== 'todos' && rowGenero !== genero) {
                mostrar = false;
            }
            
            row.style.display = mostrar ? '' : 'none';
        });
    }
    
    // Ver historial clínico completo
    function verHistorialPaciente(pacienteId) {
        window.location.href = `/medico/historial?paciente=${pacienteId}`;
    }
    
    // Agendar cita
    function agendarCita(pacienteId) {
        window.location.href = `/medico/citas/agendar?paciente=${pacienteId}`;
    }
    
    // Crear receta
    function crearReceta(pacienteId) {
        window.location.href = `/medico/recetas/crear?paciente=${pacienteId}`;
    }
    
    // Crear diagnóstico
    function crearDiagnostico(pacienteId) {
        window.location.href = `/medico/diagnosticos/crear?paciente=${pacienteId}`;
    }
    
    function cerrarModal() {
        document.getElementById('modalPaciente').classList.remove('active');
    }
    
    // Event listeners
    document.getElementById('buscadorPacientes')?.addEventListener('keyup', filtrarPacientes);
    document.getElementById('filtroGrupoEdad')?.addEventListener('change', filtrarPacientes);
    document.getElementById('filtroGenero')?.addEventListener('change', filtrarPacientes);
    
    document.getElementById('modalPaciente')?.addEventListener('click', function(e) {
        if (e.target === this) cerrarModal();
    });
    
    // Inicializar
    document.addEventListener('DOMContentLoaded', filtrarPacientes);
</script>

<style>
    /* Estadísticas de pacientes */
    .stats-pacientes {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .stat-card-paciente {
        background: white;
        border-radius: 16px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .stat-card-paciente .stat-icon {
        width: 45px;
        height: 45px;
        background: rgba(26, 95, 168, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #1a5fa8;
    }
    .stat-card-paciente .stat-number {
        font-size: 1.3rem;
        font-weight: 800;
        color: #1a2a3a;
    }
    .stat-card-paciente .stat-label {
        font-size: 0.65rem;
        color: #64748b;
    }
    
    /* Filtros */
    .filtros-pacientes {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
        background: white;
        padding: 1rem;
        border-radius: 16px;
    }
    .filtros-group {
        display: flex;
        gap: 0.5rem;
    }
    .buscador-pacientes {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #f1f5f9;
        padding: 0.5rem 1rem;
        border-radius: 30px;
    }
    .buscador-pacientes input {
        border: none;
        background: none;
        outline: none;
        font-size: 0.8rem;
        width: 220px;
    }
    
    /* Tabla de pacientes */
    .tabla-pacientes-container {
        background: white;
        border-radius: 20px;
        overflow-x: auto;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .tabla-pacientes {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }
    .tabla-pacientes th {
        text-align: left;
        padding: 1rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.8rem;
        font-weight: 600;
        color: #1a2a3a;
    }
    .tabla-pacientes td {
        padding: 1rem;
        border-bottom: 1px solid #f0f2f8;
        vertical-align: middle;
    }
    .tabla-pacientes tr:hover td {
        background: #fafcff;
    }
    
    /* Avatar paciente */
    .paciente-avatar {
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    .avatar-inicial {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, #1a5fa8, #0d9e75);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1rem;
    }
    .paciente-nombre-cell {
        font-weight: 600;
        color: #1a2a3a;
        font-size: 0.85rem;
    }
    .paciente-ultimo-diagnostico {
        font-size: 0.7rem;
        color: #64748b;
        margin-top: 2px;
    }
    
    /* Género badge */
    .genero-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .genero-badge.masculino {
        background: rgba(26, 95, 168, 0.12);
        color: #1a5fa8;
    }
    .genero-badge.femenino {
        background: rgba(236, 72, 153, 0.12);
        color: #ec4899;
    }
    
    /* Fechas */
    .fecha-cell {
        font-size: 0.8rem;
        color: #1a2a3a;
    }
    .fecha-cell.proxima {
        color: #0d9e75;
        font-weight: 500;
    }
    .sin-cita {
        font-size: 0.7rem;
        color: #94a3b8;
        font-style: italic;
    }
    
    /* Botones de acción */
    .acciones-cell {
        display: flex;
        gap: 0.5rem;
    }
    .acciones-cell button {
        width: 34px;
        height: 34px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.85rem;
    }
    .btn-ver-historial {
        background: #e2e8f0;
        color: #8b5cf6;
    }
    .btn-ver-historial:hover {
        background: #8b5cf6;
        color: white;
    }
    .btn-agendar-cita {
        background: #e2e8f0;
        color: #1a5fa8;
    }
    .btn-agendar-cita:hover {
        background: #1a5fa8;
        color: white;
    }
    .btn-receta {
        background: #e2e8f0;
        color: #f59e0b;
    }
    .btn-receta:hover {
        background: #f59e0b;
        color: white;
    }
    .btn-diagnostico {
        background: #e2e8f0;
        color: #0d9e75;
    }
    .btn-diagnostico:hover {
        background: #0d9e75;
        color: white;
    }
    
    .empty-pacientes {
        text-align: center;
        padding: 3rem;
    }
    .empty-pacientes i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }
    
    .modal-large {
        max-width: 700px;
    }
    
    @media (max-width: 900px) {
        .stats-pacientes {
            grid-template-columns: repeat(2, 1fr);
        }
        .filtros-pacientes {
            flex-direction: column;
        }
    }
    /* Filtros para pacientes */
.filtros-paciente {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1.5rem;
    background: white;
    padding: 1rem;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
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
    padding: 0.4rem 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    background: white;
    font-size: 0.75rem;
    font-family: 'Outfit', sans-serif;
    cursor: pointer;
    outline: none;
}

.filtro-select:focus {
    border-color: #1a5fa8;
}

.buscador-paciente {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #f1f5f9;
    padding: 0.5rem 1rem;
    border-radius: 30px;
}

.buscador-paciente input {
    border: none;
    background: none;
    outline: none;
    font-size: 0.8rem;
    width: 220px;
    font-family: 'Outfit', sans-serif;
}

.btn-limpiar {
    background: none;
    border: none;
    padding: 0.4rem 0.8rem;
    font-size: 0.7rem;
    cursor: pointer;
    color: #64748b;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 5px;
    border-radius: 20px;
}

.btn-limpiar:hover {
    background: #fee2e2;
    color: #ef4444;
}

/* Responsive */
@media (max-width: 900px) {
    .filtros-paciente {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filtros-left {
        flex-direction: column;
        align-items: stretch;
    }
    
    .select-group {
        justify-content: space-between;
    }
    
    .buscador-paciente input {
        width: 100%;
    }
}
</style>

</body>
</html>