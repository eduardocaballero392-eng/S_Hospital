<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Mis Citas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('css/cita.css') }}">
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
            <a href="{{ route('medico.citas') }}" class="nav-item active">
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
        <div class="page-header">
            <h1><i class="fas fa-calendar-alt"></i> Mis citas</h1>
            <p>Gestiona todas las citas programadas por tus pacientes</p>
        </div>

        {{-- FILTROS MEJORADOS --}}
        <div class="filtros-citas">
            <div class="filtros-left">
                {{-- SELECT PARA ESTADO --}}
                <div class="select-group">
                    <label><i class="fas fa-filter"></i> Estado:</label>
                    <select id="filtroEstado" class="filtro-select">
                        <option value="todas">Todas las citas</option>
                        <option value="pendiente"> Pendientes</option>
                        <option value="confirmada"> Confirmadas</option>
                        <option value="completada"> Completadas</option>
                        <option value="cancelada"> Canceladas</option>
                    </select>
                </div>


                {{-- INPUT FECHA PERSONALIZADA --}}
                <div class="select-group fecha-personalizada">
                    <label><i class="fas fa-calendar-alt"></i> Fecha específica:</label>
                    <input type="date" id="fechaPersonalizada" class="fecha-input">
                </div>
            </div>

            <div class="buscador-citas">
                <i class="fas fa-search"></i>
                <input type="text" id="buscadorCitas" placeholder="Buscar por paciente...">
                <button id="limpiarFiltros" class="btn-limpiar">
                    <i class="fas fa-eraser"></i> Limpiar
                </button>
            </div>
        </div>

        {{-- ESTADÍSTICAS RÁPIDAS --}}
        <div class="stats-citas">
            <div class="stat-cita">
                <div class="stat-icon"><i class="fas fa-calendar-week"></i></div>
                <div class="stat-info">
                    <div class="stat-number" id="totalMostrados">{{ $totalCitas ?? 0 }}</div>
                    <div class="stat-label">Citas mostradas</div>
                </div>
            </div>
            <div class="stat-cita">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $citasPendientes ?? 0 }}</div>
                    <div class="stat-label">Pendientes</div>
                </div>
            </div>
            <div class="stat-cita">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $citasConfirmadas ?? 0 }}</div>
                    <div class="stat-label">Confirmadas</div>
                </div>
            </div>
            <div class="stat-cita">
                <div class="stat-icon"><i class="fas fa-check-double"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $citasCompletadas ?? 0 }}</div>
                    <div class="stat-label">Completadas</div>
                </div>
            </div>
        </div>

        {{-- TABLA DE CITAS --}}
        <div class="tabla-citas-container">
            <table class="tabla-citas">
                <thead>
                    <tr>
                        <th>Fecha y hora</th>
                        <th>Paciente</th>
                        <th>DNI</th>
                        <th>Teléfono</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaCitasBody">
                    @if(isset($citas) && count($citas) > 0)
                        @foreach($citas as $cita)
                        <tr data-estado="{{ $cita->estado }}" 
                            data-fecha="{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('Y-m-d') }}"
                            data-paciente="{{ strtolower($cita->paciente->nombre ?? '') }} {{ strtolower($cita->paciente->apellido ?? '') }}">
                            <td>
                                <div class="fecha-cita">
                                    <span class="fecha-dia">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y') }}</span>
                                    <span class="fecha-hora">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="paciente-nombre">
                                    {{ $cita->paciente->nombre ?? 'Paciente' }} {{ $cita->paciente->apellido ?? '' }}
                                </div>
                            </td>
                            <td>{{ $cita->paciente->DNI ?? 'N/A' }}</td>
                            <td>{{ $cita->paciente->telefono ?? 'N/A' }}</td>
                            <td>{{ $cita->tipo ?? 'Consulta general' }}</td>
                            <td>
                                <span class="estado-badge {{ $cita->estado }}">
                                    @switch($cita->estado)
                                        @case('pendiente') Pendiente @break
                                        @case('confirmada') Confirmada @break
                                        @case('completada') Completada @break
                                        @case('cancelada') Cancelada @break
                                        @default {{ ucfirst($cita->estado) }}
                                    @endswitch
                                </span>
                            </td>
                            <td class="acciones-cell">
                                <button class="btn-ver" onclick="verDetalleCita({{ $cita->id }})" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if($cita->estado == 'pendiente')
                                <button class="btn-confirmar" onclick="confirmarCita({{ $cita->id }})" title="Confirmar cita">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn-cancelar" onclick="cancelarCita({{ $cita->id }})" title="Cancelar cita">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                                @if($cita->estado == 'confirmada')
                                <button class="btn-completar" onclick="completarCita({{ $cita->id }})" title="Marcar como completada">
                                    <i class="fas fa-check-double"></i>
                                </button>
                                @endif
                                <button class="btn-historial" onclick="verHistorialPaciente({{ $cita->paciente->id ?? 0 }})" title="Ver historial">
                                    <i class="fas fa-history"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr class="empty-row">
                            <td colspan="7">
                                <div class="empty-citas">
                                    <i class="fas fa-calendar-day"></i>
                                    <p>No hay citas programadas</p>
                                    <span>Los pacientes aún no han agendado citas contigo</span>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </main>
</div>

{{-- MODAL DETALLE CITA --}}
<div class="modal-overlay" id="modalCita">
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-calendar-check"></i> Detalle de la cita</h3>
            <button class="modal-close" onclick="cerrarModal()">&times;</button>
        </div>
        <div class="modal-body" id="modalCitaBody">
            <!-- Contenido dinámico -->
        </div>
    </div>
</div>

<script>
    // Función para filtrar citas por estado y fecha
    function filtrarCitas() {
        const estado = document.getElementById('filtroEstado').value;
        const tipoFecha = document.getElementById('filtroFecha').value;
        const fechaPersonalizada = document.getElementById('fechaPersonalizada').value;
        const searchTerm = document.getElementById('buscadorCitas').value.toLowerCase();
        
        const rows = document.querySelectorAll('#tablaCitasBody tr');
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        
        const hoyStr = hoy.toISOString().split('T')[0];
        let visibleCount = 0;
        
        // Calcular fecha de mañana
        const manana = new Date(hoy);
        manana.setDate(hoy.getDate() + 1);
        const mananaStr = manana.toISOString().split('T')[0];
        
        // Calcular inicio de semana (lunes)
        const inicioSemana = new Date(hoy);
        const diaSemana = hoy.getDay();
        const inicio = diaSemana === 0 ? -6 : 1 - diaSemana;
        inicioSemana.setDate(hoy.getDate() + inicio);
        const inicioSemanaStr = inicioSemana.toISOString().split('T')[0];
        
        // Calcular fin de semana (domingo)
        const finSemana = new Date(inicioSemana);
        finSemana.setDate(inicioSemana.getDate() + 6);
        const finSemanaStr = finSemana.toISOString().split('T')[0];
        
        // Calcular inicio y fin de mes
        const inicioMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
        const finMes = new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0);
        const inicioMesStr = inicioMes.toISOString().split('T')[0];
        const finMesStr = finMes.toISOString().split('T')[0];
        
        rows.forEach(row => {
            if (row.classList.contains('empty-row')) return;
            
            const rowEstado = row.getAttribute('data-estado');
            const rowFecha = row.getAttribute('data-fecha');
            const paciente = row.getAttribute('data-paciente') || '';
            
            let mostrar = true;
            
            // Filtrar por estado
            if (estado !== 'todas' && rowEstado !== estado) {
                mostrar = false;
            }
            
            // Filtrar por fecha
            if (mostrar && tipoFecha !== 'todas') {
                switch(tipoFecha) {
                    case 'hoy':
                        if (rowFecha !== hoyStr) mostrar = false;
                        break;
                    case 'manana':
                        if (rowFecha !== mananaStr) mostrar = false;
                        break;
                    case 'semana':
                        if (rowFecha < inicioSemanaStr || rowFecha > finSemanaStr) mostrar = false;
                        break;
                    case 'mes':
                        if (rowFecha < inicioMesStr || rowFecha > finMesStr) mostrar = false;
                        break;
                    case 'pasadas':
                        if (rowFecha >= hoyStr) mostrar = false;
                        break;
                    case 'proximas':
                        if (rowFecha < hoyStr) mostrar = false;
                        break;
                }
            }
            
            // Filtrar por fecha personalizada
            if (mostrar && fechaPersonalizada !== '') {
                if (rowFecha !== fechaPersonalizada) mostrar = false;
            }
            
            // Filtrar por búsqueda
            if (mostrar && searchTerm !== '' && !paciente.includes(searchTerm)) {
                mostrar = false;
            }
            
            row.style.display = mostrar ? '' : 'none';
            if (mostrar) visibleCount++;
        });
        
        document.getElementById('totalMostrados').innerText = visibleCount;
    }
    
    // Event listeners para los filtros
    document.getElementById('filtroEstado')?.addEventListener('change', filtrarCitas);
    document.getElementById('filtroFecha')?.addEventListener('change', function() {
        // Limpiar fecha personalizada cuando se selecciona otra opción
        if (this.value !== 'todas') {
            document.getElementById('fechaPersonalizada').value = '';
        }
        filtrarCitas();
    });
    document.getElementById('fechaPersonalizada')?.addEventListener('change', function() {
        if (this.value !== '') {
            document.getElementById('filtroFecha').value = 'todas';
        }
        filtrarCitas();
    });
    document.getElementById('buscadorCitas')?.addEventListener('keyup', filtrarCitas);
    
    // Botón limpiar filtros
    document.getElementById('limpiarFiltros')?.addEventListener('click', function() {
        document.getElementById('filtroEstado').value = 'todas';
        document.getElementById('filtroFecha').value = 'hoy';
        document.getElementById('fechaPersonalizada').value = '';
        document.getElementById('buscadorCitas').value = '';
        filtrarCitas();
    });
    
    // Ver detalle de cita
    function verDetalleCita(citaId) {
        fetch(`/medico/citas/${citaId}/detalle`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const cita = data.cita;
                    document.getElementById('modalCitaBody').innerHTML = `
                        <div class="detalle-cita">
                            <div class="detalle-row">
                                <div class="detalle-label">Paciente:</div>
                                <div class="detalle-value">${cita.paciente.nombre} ${cita.paciente.apellido || ''}</div>
                            </div>
                            <div class="detalle-row">
                                <div class="detalle-label">DNI:</div>
                                <div class="detalle-value">${cita.paciente.DNI || 'N/A'}</div>
                            </div>
                            <div class="detalle-row">
                                <div class="detalle-label">Teléfono:</div>
                                <div class="detalle-value">${cita.paciente.telefono || 'N/A'}</div>
                            </div>
                            <div class="detalle-row">
                                <div class="detalle-label">Fecha:</div>
                                <div class="detalle-value">${new Date(cita.fecha_hora).toLocaleDateString('es-ES')}</div>
                            </div>
                            <div class="detalle-row">
                                <div class="detalle-label">Hora:</div>
                                <div class="detalle-value">${new Date(cita.fecha_hora).toLocaleTimeString('es-ES', {hour:'2-digit', minute:'2-digit'})}</div>
                            </div>
                            <div class="detalle-row">
                                <div class="detalle-label">Tipo de cita:</div>
                                <div class="detalle-value">${cita.tipo || 'Consulta general'}</div>
                            </div>
                            <div class="detalle-row">
                                <div class="detalle-label">Motivo:</div>
                                <div class="detalle-value">${cita.motivo || 'No especificado'}</div>
                            </div>
                            <div class="detalle-row">
                                <div class="detalle-label">Estado:</div>
                                <div class="detalle-value"><span class="estado-badge ${cita.estado}">${cita.estado}</span></div>
                            </div>
                        </div>
                    `;
                    document.getElementById('modalCita').classList.add('active');
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    function confirmarCita(citaId) {
        if (confirm('¿Confirmar esta cita?')) {
            fetch(`/medico/citas/${citaId}/confirmar`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => response.json()).then(data => {
                if (data.success) location.reload();
                else alert('Error al confirmar la cita');
            });
        }
    }
    
    function cancelarCita(citaId) {
        if (confirm('¿Cancelar esta cita?')) {
            fetch(`/medico/citas/${citaId}/cancelar`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => response.json()).then(data => {
                if (data.success) location.reload();
                else alert('Error al cancelar la cita');
            });
        }
    }
    
    function completarCita(citaId) {
        if (confirm('¿Marcar esta cita como completada?')) {
            fetch(`/medico/citas/${citaId}/completar`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => response.json()).then(data => {
                if (data.success) location.reload();
                else alert('Error al completar la cita');
            });
        }
    }
    
    function verHistorialPaciente(pacienteId) {
        if (pacienteId) {
            window.location.href = `/medico/pacientes/${pacienteId}/historial`;
        }
    }
    
    function cerrarModal() {
        document.getElementById('modalCita').classList.remove('active');
    }
    
    document.getElementById('modalCita')?.addEventListener('click', function(e) {
        if (e.target === this) cerrarModal();
    });
    
    // Inicializar filtro (mostrar citas de hoy)
    document.addEventListener('DOMContentLoaded', function() {
        filtrarCitas();
    });
</script>

<style>
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
    .fecha-personalizada {
        background: #f1f5f9;
    }
    .fecha-input {
        padding: 0.4rem 0.8rem;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        font-size: 0.75rem;
        font-family: 'Outfit', sans-serif;
        outline: none;
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
    }
    .btn-limpiar:hover {
        color: #ef4444;
    }
    @media (max-width: 900px) {
        .filtros-left {
            flex-direction: column;
            align-items: stretch;
        }
        .select-group {
            justify-content: space-between;
        }
    }
</style>

</body>
</html>