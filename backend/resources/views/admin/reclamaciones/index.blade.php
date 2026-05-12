<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Administrar Reclamaciones</title>
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
            <h1><i class="fas fa-file-alt"></i> Administrar Reclamaciones</h1>
            <div class="admin-user">
                <div class="user-info">{{ Auth::user()->nombre }}</div>
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}</div>
            </div>
        </div>

        <div class="actions-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchReclamacion" placeholder="Buscar por nombre o DNI...">
            </div>
        </div>

        <div class="data-panel">
            <div class="panel-header">
                <h3><i class="fas fa-list"></i> Lista de reclamaciones</h3>
            </div>

            <div class="table-responsive">
                <table class="data-table" id="tablaReclamaciones">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>DNI</th>
                            <th>Email</th>
                            <th>Tipo</th>
                            <th class="col-acciones">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reclamaciones ?? [] as $reclamacion)
                        <tr data-nombre="{{ strtolower($reclamacion->nombre) }} {{ strtolower($reclamacion->apellido) }}"
                            data-dni="{{ $reclamacion->nro_documento }}">
                            <td>{{ $reclamacion->id }}</td>
                            <td>{{ $reclamacion->nombre }} {{ $reclamacion->apellido }}</td>
                            <td>{{ $reclamacion->nro_documento }}</td>
                            <td>{{ $reclamacion->email }}</td>
                            <td>{{ $reclamacion->tipo_reclamo }}</td>
                            <td class="acciones-cell">
                                <button class="btn-ver" onclick="verReclamacion({{ $reclamacion->id }})"><i class="fas fa-eye"></i></button>
                                <button class="btn-eliminar" onclick="eliminarReclamacion({{ $reclamacion->id }})"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="empty-row">No hay reclamaciones registradas</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(isset($reclamaciones) && method_exists($reclamaciones, 'links'))
                <div class="pagination-container">{{ $reclamaciones->links() }}</div>
            @endif
        </div>
    </main>
</div>

<script>
    document.getElementById('searchReclamacion')?.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tablaReclamaciones tbody tr');
        rows.forEach(row => {
            if (row.querySelector('.empty-row')) return;
            const nombre = row.getAttribute('data-nombre') || '';
            const dni = row.getAttribute('data-dni') || '';
            row.style.display = (nombre.includes(searchTerm) || dni.includes(searchTerm)) ? '' : 'none';
        });
    });

    function verReclamacion(id) {
        window.location.href = `{{ url('/admin/pacientes/reclamaciones') }}/${id}`;
    }

    function eliminarReclamacion(id) {
        if (confirm('¿Eliminar esta reclamación?')) {
            fetch(`{{ url('/admin/pacientes/reclamaciones') }}/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(() => location.reload());
        }
    }
</script>

<style>
    .actions-bar { display: flex; justify-content: flex-end; margin-bottom: 1rem; }
    .search-box { display: flex; align-items: center; gap: 0.5rem; background: white; padding: 0.3rem 1rem; border-radius: 30px; }
    .search-box input { border: none; outline: none; width: 220px; }
    .acciones-cell { text-align: center; }
    .btn-ver, .btn-eliminar { background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; }
    .btn-ver { color: #1a5fa8; }
    .btn-eliminar { color: #ef4444; }
</style>

</body>
</html>