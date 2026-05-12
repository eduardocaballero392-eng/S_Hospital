<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Administrar Médicos</title>
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
            <h1><i class="fas fa-user-md"></i> Administrar Médicos</h1>
            <div class="admin-user">
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->nombre }}</div>
                    <div class="user-role">Administrador</div>
                </div>
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}</div>
            </div>
        </div>

        <div class="actions-bar">
            <a href="{{ route('admin.medicos.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Nuevo médico</a>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchMedico" placeholder="Buscar por nombre...">
            </div>
        </div>

        <div class="data-panel">
            <div class="panel-header">
                <h3><i class="fas fa-list"></i> Lista de médicos</h3>
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Médico</th>
                            <th>Especialidad</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th class="col-acciones">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicos ?? [] as $medico)
                        <tr>
                            <td>{{ $medico->id }}</td>
                            <td>{{ $medico->nombre }}</td>
                            <td>{{ $medico->especialidad ?? 'N/A' }}</td>
                            <td>{{ $medico->telefono ?? 'N/A' }}</td>
                            <td>{{ $medico->email ?? 'N/A' }}</td>
                            <td>
                                @php $activo = $medico->usuario?->isEstadoActivo(); @endphp
                                <span class="estado-badge {{ $activo ? 'activo' : 'inactivo' }}">
                                    {{ $activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="acciones acciones-cell">
                                <button class="btn-ver" onclick="verMedico({{ $medico->id }})"><i class="fas fa-eye"></i></button>
                                <button class="btn-editar" onclick="editarMedico({{ $medico->id }})"><i class="fas fa-edit"></i></button>
                                <button class="btn-eliminar" onclick="eliminarMedico({{ $medico->id }})"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center">No hay médicos registrados</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<style>
    .actions-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .btn-primary {
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
    }
    .search-box {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        padding: 0.3rem 1rem;
        border-radius: 30px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .search-box input {
        border: none;
        outline: none;
        font-size: 0.8rem;
        width: 200px;
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
    }
    .estado-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .estado-badge.activo {
        background: rgba(13, 158, 117, 0.15);
        color: #0d9e75;
    }
    .estado-badge.inactivo {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
    }
    .acciones {
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
    .text-center { text-align: center; }
</style>

<script>
    document.getElementById('searchMedico')?.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.data-table tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    function verMedico(id) { window.location.href = `/admin/medicos/${id}`; }
    function editarMedico(id) { window.location.href = `/admin/medicos/${id}/edit`; }
    function eliminarMedico(id) {
        if (confirm('¿Eliminar este médico?')) {
            fetch(`/admin/medicos/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(() => location.reload());
        }
    }
</script>

</body>
</html>