<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Mis Diagnósticos</title>
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
            <a href="{{ route('medico.pacientes') }}" class="nav-item">
                <i class="fas fa-users"></i>
                <span>Mis pacientes</span>
            </a>
           
            <a href="{{ route('medico.diagnosticos') }}" class="nav-item active">
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
            <h1><i class="fas fa-file-alt"></i> Mis diagnósticos</h1>
            <p>Registra y gestiona los diagnósticos de tus pacientes</p>
        </div>

        <div class="accion-superior">
            <a href="{{ route('medico.diagnosticos.crear') }}" class="btn-nuevo">
                <i class="fas fa-plus"></i> Nuevo diagnóstico
            </a>
        </div>

        {{-- FILTROS --}}
        <div class="filtros-paciente">
            <div class="filtros-left">
                <div class="select-group">
                    <label><i class="fas fa-user"></i> Paciente:</label>
                    <select id="filtroPaciente" class="filtro-select">
                        <option value="todos">Todos los pacientes</option>
                        @foreach($pacientes ?? [] as $paciente)
                            <option value="{{ $paciente->id }}">{{ $paciente->nombre }} {{ $paciente->apellido ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="buscador-paciente">
                <i class="fas fa-search"></i>
                <input type="text" id="buscadorDiagnosticos" placeholder="Buscar por diagnóstico...">
            </div>
        </div>

        {{-- TABLA --}}
        <div class="tabla-container">
            <table class="tabla-diagnosticos">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Diagnóstico</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaBody">
                    @if(isset($diagnosticos) && count($diagnosticos) > 0)
                        @foreach($diagnosticos as $diagnostico)
                        <tr data-paciente="{{ $diagnostico->paciente_id }}" data-nombre="{{ strtolower($diagnostico->nombre) }}">
                            <td>{{ \Carbon\Carbon::parse($diagnostico->fecha_diagnostico)->format('d/m/Y') }}</td>
                            <td>{{ $diagnostico->paciente->nombre ?? 'N/A' }} {{ $diagnostico->paciente->apellido ?? '' }}</td>
                            <td><strong>{{ $diagnostico->nombre }}</strong></td>
                            <td>{{ Str::limit($diagnostico->descripcion, 60) }}</td>
                            <td class="acciones-cell">
                                <button class="btn-ver" onclick="verDiagnostico({{ $diagnostico->id }})" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-editar" onclick="editarDiagnostico({{ $diagnostico->id }})" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-eliminar" onclick="eliminarDiagnostico({{ $diagnostico->id }})" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr class="empty-row">
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fas fa-file-alt"></i>
                                    <p>No hay diagnósticos registrados</p>
                                    <span>Comienza registrando tu primer diagnóstico</span>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </main>
</div>

<script>
    document.getElementById('filtroPaciente')?.addEventListener('change', function() {
        const pacienteId = this.value;
        const rows = document.querySelectorAll('#tablaBody tr');
        rows.forEach(row => {
            if (row.classList.contains('empty-row')) return;
            const rowPaciente = row.getAttribute('data-paciente');
            row.style.display = (pacienteId === 'todos' || rowPaciente === pacienteId) ? '' : 'none';
        });
    });
    
    document.getElementById('buscadorDiagnosticos')?.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tablaBody tr');
        rows.forEach(row => {
            if (row.classList.contains('empty-row')) return;
            const nombre = row.getAttribute('data-nombre') || '';
            row.style.display = nombre.includes(searchTerm) ? '' : 'none';
        });
    });
    
    function verDiagnostico(id) {
        window.location.href = `/medico/diagnosticos/${id}`;
    }
    
    function editarDiagnostico(id) {
        window.location.href = `/medico/diagnosticos/${id}/editar`;
    }
    
    function eliminarDiagnostico(id) {
        if (confirm('¿Eliminar este diagnóstico?')) {
            fetch(`/medico/diagnosticos/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => location.reload());
        }
    }
</script>

<style>
    .accion-superior {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 1rem;
    }
    
    .btn-nuevo {
        background: linear-gradient(135deg, #1a5fa8, #0d9e75);
        color: white;
        padding: 0.5rem 1.2rem;
        border-radius: 30px;
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    
    .btn-nuevo:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26, 95, 168, 0.3);
        color: white;
    }
    
    .tabla-container {
        background: white;
        border-radius: 20px;
        overflow-x: auto;
    }
    
    .tabla-diagnosticos {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }
    
    .tabla-diagnosticos th {
        text-align: left;
        padding: 1rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.8rem;
        font-weight: 600;
        color: #1a2a3a;
    }
    
    .tabla-diagnosticos td {
        padding: 1rem;
        border-bottom: 1px solid #f0f2f8;
        vertical-align: middle;
    }
    
    .btn-ver, .btn-editar, .btn-eliminar {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin: 0 2px;
        transition: all 0.2s;
    }
    
    .btn-ver { background: #e2e8f0; color: #1a5fa8; }
    .btn-ver:hover { background: #1a5fa8; color: white; }
    .btn-editar { background: #e2e8f0; color: #f59e0b; }
    .btn-editar:hover { background: #f59e0b; color: white; }
    .btn-eliminar { background: #e2e8f0; color: #ef4444; }
    .btn-eliminar:hover { background: #ef4444; color: white; }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    /* Filtros - Mismo estilo que citas y pacientes */
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

.buscador-paciente input::placeholder {
    color: #94a3b8;
}

/* Botón nuevo diagnóstico */
.accion-superior {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 1rem;
}

.btn-nuevo {
    background: linear-gradient(135deg, #1a5fa8, #0d9e75);
    color: white;
    padding: 0.5rem 1.2rem;
    border-radius: 30px;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}

.btn-nuevo:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(26, 95, 168, 0.3);
    color: white;
}

/* Tabla de diagnósticos */
.tabla-container {
    background: white;
    border-radius: 20px;
    overflow-x: auto;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
}

.tabla-diagnosticos {
    width: 100%;
    border-collapse: collapse;
    min-width: 700px;
}

.tabla-diagnosticos th {
    text-align: left;
    padding: 1rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    font-size: 0.8rem;
    font-weight: 600;
    color: #1a2a3a;
}

.tabla-diagnosticos td {
    padding: 1rem;
    border-bottom: 1px solid #f0f2f8;
    vertical-align: middle;
    font-size: 0.85rem;
    color: #1a2a3a;
}

.tabla-diagnosticos tr:hover td {
    background: #fafcff;
}

/* Botones de acción */
.acciones-cell {
    display: flex;
    gap: 0.5rem;
}

.acciones-cell button {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 0.8rem;
}

.btn-ver {
    background: #e2e8f0;
    color: #1a5fa8;
}

.btn-ver:hover {
    background: #1a5fa8;
    color: white;
}

.btn-editar {
    background: #e2e8f0;
    color: #f59e0b;
}

.btn-editar:hover {
    background: #f59e0b;
    color: white;
}

.btn-eliminar {
    background: #e2e8f0;
    color: #ef4444;
}

.btn-eliminar:hover {
    background: #ef4444;
    color: white;
}

/* Estado vacío */
.empty-state {
    text-align: center;
    padding: 3rem;
}

.empty-state i {
    font-size: 3rem;
    color: #cbd5e1;
    margin-bottom: 1rem;
}

.empty-state p {
    font-size: 0.9rem;
    color: #1a2a3a;
    margin-bottom: 0.3rem;
}

.empty-state span {
    font-size: 0.8rem;
    color: #64748b;
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