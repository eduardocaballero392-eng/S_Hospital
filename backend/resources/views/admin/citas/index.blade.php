<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Administrar Citas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('css/admin.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-dash">

<div class="admin-layout">
    @include('admin.partials.sidebar')

    <main class="admin-main">
        <div class="admin-header">
            <h1><i class="fas fa-calendar-alt"></i> Administrar Citas</h1>
            <div class="admin-user">
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->nombre }}</div>
                    <div class="user-role">Administrador</div>
                </div>
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}</div>
            </div>
        </div>

        <div class="actions-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchCita" placeholder="Buscar por paciente o médico...">
            </div>
        </div>

        <div class="data-panel">
            <div class="panel-header">
                <h3><i class="fas fa-list"></i> Lista de citas</h3>
            </div>

            <div class="table-responsive">
                <table class="data-table" id="tablaCitas">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>Médico</th>
                            <th>Fecha y hora</th>
                            <th>Estado</th>
                            <th class="col-acciones">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($citas ?? [] as $cita)
                        <tr data-paciente="{{ strtolower($cita->paciente->nombre ?? '') }} {{ strtolower($cita->paciente->apellido ?? '') }}"
                            data-medico="{{ strtolower($cita->medico->nombre ?? '') }}">
                            <td>{{ $cita->id }}</td>
                            <td>
                                <div class="table-user">
                                    <div class="user-avatar-small">{{ strtoupper(substr($cita->paciente->nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($cita->paciente->apellido ?? '', 0, 1)) }}</div>
                                    <div>{{ $cita->paciente->nombre ?? 'N/A' }} {{ $cita->paciente->apellido ?? '' }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="table-user">
                                    <div class="user-avatar-small">{{ strtoupper(substr($cita->medico->nombre ?? 'M', 0, 1)) }}</div>
                                    <div>{{ $cita->medico->nombre ?? 'No asignado' }}</div>
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y H:i') }}</td>
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
                                <button class="btn-ver" onclick="verCita({{ $cita->id }})" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-editar" onclick="editarCita({{ $cita->id }})" title="Editar cita">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-eliminar" onclick="eliminarCita({{ $cita->id }})" title="Eliminar cita">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="empty-row">No hay citas registradas</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(isset($citas) && method_exists($citas, 'links'))
                <div class="pagination-container">
                    {{ $citas->links() }}
                </div>
            @endif
        </div>
    </main>
</div>

<script>
    // Búsqueda en tiempo real
    document.getElementById('searchCita')?.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tablaCitas tbody tr');
        
        rows.forEach(row => {
            if (row.querySelector('.empty-row')) return;
            const paciente = row.getAttribute('data-paciente') || '';
            const medico = row.getAttribute('data-medico') || '';
            if (paciente.includes(searchTerm) || medico.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    function verCita(id) {
        window.location.href = `/admin/citas/${id}`;
    }

    function editarCita(id) {
        window.location.href = `/admin/citas/${id}/edit`;
    }

    function eliminarCita(id) {
        if (confirm('¿Eliminar esta cita?')) {
            fetch(`/admin/citas/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => response.json()).then(data => {
                if (data.success) location.reload();
                else alert('Error al eliminar la cita');
            });
        }
    }
</script>

<style>
    .actions-bar {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        padding: 0.4rem 1rem;
        border-radius: 30px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .search-box input {
        border: none;
        outline: none;
        font-size: 0.8rem;
        width: 220px;
        font-family: 'Outfit', sans-serif;
    }

    .data-panel {
        background: white;
        border-radius: 20px;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .panel-header {
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        text-align: left;
        padding: 0.8rem;
        background: #f8fafc;
        font-size: 0.75rem;
        font-weight: 600;
        color: #1a2a3a;
    }

    .data-table td {
        padding: 0.8rem;
        border-bottom: 1px solid #f0f2f8;
        font-size: 0.8rem;
        vertical-align: middle;
    }

    .table-user {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-avatar-small {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #1a5fa8, #0d9e75);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.7rem;
    }

    .estado-badge {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .estado-badge.pendiente {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
    }

    .estado-badge.confirmada {
        background: rgba(26, 95, 168, 0.15);
        color: #1a5fa8;
    }

    .estado-badge.completada {
        background: rgba(13, 158, 117, 0.15);
        color: #0d9e75;
    }

    .estado-badge.cancelada {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
    }

    .acciones-cell {
        text-align: center;
    }

    .btn-ver, .btn-editar, .btn-eliminar {
        background: #f1f5f9;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-ver { color: #1a5fa8; }
    .btn-ver:hover { background: #1a5fa8; color: white; }

    .btn-editar { color: #f59e0b; }
    .btn-editar:hover { background: #f59e0b; color: white; }

    .btn-eliminar { color: #ef4444; }
    .btn-eliminar:hover { background: #ef4444; color: white; }

    .empty-row td {
        text-align: center;
        padding: 2rem;
        color: #94a3b8;
    }

    .pagination-container {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }

    @media (max-width: 900px) {
        .actions-bar {
            justify-content: stretch;
        }
        .search-box input {
            width: 100%;
        }
    }
</style>

</body>
</html>