<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Administrar Pacientes</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('css/admin.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-dash">

<div class="admin-layout">
    @include('admin.partials.sidebar')

    {{-- MAIN CONTENT --}}
    <main class="admin-main">
        <div class="admin-header">
            <h1><i class="fas fa-users"></i> Administrar Pacientes</h1>
            <div class="admin-user">
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->nombre }}</div>
                    <div class="user-role">Administrador</div>
                </div>
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}</div>
            </div>
        </div>

        {{-- BARRA DE ACCIONES --}}
        <div class="actions-bar">
            <a href="{{ route('admin.pacientes.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i> Nuevo paciente
            </a>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchPaciente" placeholder="Buscar por nombre, DNI o teléfono...">
            </div>
        </div>

        {{-- TABLA DE PACIENTES --}}
        <div class="data-panel">
            <div class="panel-header">
                <h3><i class="fas fa-list"></i> Lista de pacientes</h3>
                <div class="filter-info">
                    Mostrando {{ $pacientes->firstItem() ?? 0 }} - {{ $pacientes->lastItem() ?? 0 }} de {{ $pacientes->total() ?? 0 }} pacientes
                </div>
            </div>

            <div class="table-responsive">
                <table class="data-table" id="tablaPacientes">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>DNI</th>
                            <th>Teléfono</th>
                            <th>Médico asignado</th>
                            <th>Estado</th>
                            <th class="col-acciones">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pacientes ?? [] as $paciente)
                        <tr data-nombre="{{ strtolower($paciente->nombre) }} {{ strtolower($paciente->apellido ?? '') }}"
                            data-dni="{{ $paciente->DNI ?? '' }}"
                            data-telefono="{{ $paciente->telefono ?? '' }}">
                            <td>{{ $paciente->id }}</td>
                            <td>
                                <div class="table-user">
                                    <div class="user-avatar-small">{{ strtoupper(substr($paciente->nombre, 0, 1)) }}{{ strtoupper(substr($paciente->apellido ?? '', 0, 1)) }}</div>
                                    <div>
                                        <div class="user-name-table">{{ $paciente->nombre }} {{ $paciente->apellido ?? '' }}</div>
                                        <div class="user-email-table">{{ $paciente->email ?? 'Sin email' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $paciente->DNI ?? 'N/A' }}</td>
                            <td>{{ $paciente->telefono ?? 'N/A' }}</td>
                            <td>
                                @if($paciente->medicoAsignado)
                                    <span class="badge medico">{{ $paciente->medicoAsignado->nombre }}</span>
                                @else
                                    <span class="badge sin-asignar">No asignado</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $paciente->usuario && $paciente->usuario->estado == 1 ? 'active' : 'inactive' }}">
                                    {{ $paciente->usuario && $paciente->usuario->estado == 1 ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="actions-cell">
                                <button class="btn-icon view" onclick="verPaciente({{ $paciente->id }})" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-icon edit" onclick="editarPaciente({{ $paciente->id }})" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                               
                                
                                <button class="btn-icon delete" onclick="eliminarPaciente({{ $paciente->id }}, '{{ $paciente->nombre }}')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="empty-row">
                                <i class="fas fa-users fa-2x"></i>
                                <p>No hay pacientes registrados</p>
                                <span>Haz clic en "Nuevo paciente" para agregar</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINACIÓN --}}
            @if(isset($pacientes) && method_exists($pacientes, 'links'))
                <div class="pagination-container">
                    {{ $pacientes->links() }}
                </div>
            @endif
        </div>
    </main>
</div>

{{-- MODAL PARA ASIGNAR MÉDICO --}}
<div class="modal-overlay" id="modalAsignarMedico">
    <div class="modal-container modal-medium">
        <div class="modal-header">
            <h3><i class="fas fa-user-md"></i> Asignar médico</h3>
            <button class="modal-close" onclick="cerrarModalAsignar()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="formAsignarMedico" method="POST">
                @csrf
                <input type="hidden" id="paciente_id" name="paciente_id">
                <div class="form-group">
                    <label><i class="fas fa-user-md"></i> Seleccionar médico</label>
                    <select name="medico_id" id="medico_id" class="form-control" required>
                        <option value="">-- Seleccione un médico --</option>
                        @foreach($medicos ?? [] as $medico)
                            <option value="{{ $medico->id }}">{{ $medico->nombre }} - {{ $medico->especialidad }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn-cancelar" onclick="cerrarModalAsignar()">Cancelar</button>
                    <button type="submit" class="btn-guardar">Asignar médico</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Búsqueda en tiempo real
    document.getElementById('searchPaciente')?.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tablaPacientes tbody tr');
        
        rows.forEach(row => {
            if (row.querySelector('.empty-row')) return;
            
            const nombre = row.getAttribute('data-nombre') || '';
            const dni = row.getAttribute('data-dni') || '';
            const telefono = row.getAttribute('data-telefono') || '';
            
            if (nombre.includes(searchTerm) || dni.includes(searchTerm) || telefono.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    function verPaciente(id) {
        window.location.href = `/admin/pacientes/${id}`;
    }

    function editarPaciente(id) {
        window.location.href = `/admin/pacientes/${id}/edit`;
    }

    function eliminarPaciente(id, nombre) {
        if (confirm(`¿Eliminar al paciente ${nombre}? Esta acción no se puede deshacer.`)) {
            fetch(`/admin/pacientes/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error al eliminar el paciente');
                }
            });
        }
    }

    function asignarMedico(id, nombre) {
        document.getElementById('paciente_id').value = id;
        document.getElementById('formAsignarMedico').action = `/admin/pacientes/${id}/asignar-medico`;
        document.getElementById('modalAsignarMedico').classList.add('active');
        
        // Actualizar el título del modal
        document.querySelector('#modalAsignarMedico .modal-header h3').innerHTML = `<i class="fas fa-user-md"></i> Asignar médico a ${nombre}`;
    }

    function cerrarModalAsignar() {
        document.getElementById('modalAsignarMedico').classList.remove('active');
    }

    // Cerrar modal al hacer clic fuera
    document.getElementById('modalAsignarMedico')?.addEventListener('click', function(e) {
        if (e.target === this) cerrarModalAsignar();
    });
</script>

<style>
    .actions-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #1a5fa8, #0d9e75);
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 30px;
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26, 95, 168, 0.3);
        color: white;
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        padding: 0.4rem 1rem;
        border-radius: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    .search-box input {
        border: none;
        background: none;
        outline: none;
        font-size: 0.8rem;
        width: 220px;
        font-family: 'Outfit', sans-serif;
    }

    .filter-info {
        font-size: 0.7rem;
        color: #64748b;
    }

    .user-name-table {
        font-weight: 600;
        font-size: 0.85rem;
        color: #1a2a3a;
    }

    .user-email-table {
        font-size: 0.65rem;
        color: #94a3b8;
    }

    .badge.medico {
        background: rgba(26, 95, 168, 0.15);
        color: #1a5fa8;
    }

    .badge.sin-asignar {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
    }

    .badge.active {
        background: rgba(13, 158, 117, 0.15);
        color: #0d9e75;
    }

    .badge.inactive {
        background: rgba(100, 116, 139, 0.15);
        color: #64748b;
    }

    .actions-cell {
        text-align: center;
    }

    .btn-icon {
        background: #f1f5f9;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-icon.view { color: #1a5fa8; }
    .btn-icon.view:hover { background: #1a5fa8; color: white; }
    .btn-icon.edit { color: #f59e0b; }
    .btn-icon.edit:hover { background: #f59e0b; color: white; }
    .btn-icon.assign { color: #0d9e75; }
    .btn-icon.assign:hover { background: #0d9e75; color: white; }
    .btn-icon.delete { color: #ef4444; }
    .btn-icon.delete:hover { background: #ef4444; color: white; }

    .modal-medium {
        max-width: 500px;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #1a2a3a;
        margin-bottom: 0.5rem;
    }

    .form-group label i {
        color: #1a5fa8;
        margin-right: 6px;
    }

    .form-control {
        width: 100%;
        padding: 0.6rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.85rem;
        font-family: 'Outfit', sans-serif;
    }

    .form-control:focus {
        outline: none;
        border-color: #1a5fa8;
    }

    .modal-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .btn-cancelar {
        background: #f1f5f9;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.8rem;
    }

    .btn-guardar {
        background: #0d9e75;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        color: white;
        cursor: pointer;
        font-size: 0.8rem;
    }

    .pagination-container {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }

    .empty-row {
        text-align: center;
        padding: 3rem;
    }

    .empty-row i {
        font-size: 2rem;
        color: #cbd5e1;
        margin-bottom: 0.5rem;
    }

    @media (max-width: 900px) {
        .actions-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box input {
            width: 100%;
        }
    }
</style>

</body>
</html>