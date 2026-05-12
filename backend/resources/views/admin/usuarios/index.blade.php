<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Administrar Usuarios</title>
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
            <h1><i class="fas fa-user-cog"></i> Administrar Usuarios</h1>
            <div class="admin-user">
                <div class="user-info">{{ Auth::user()->nombre }}</div>
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}</div>
            </div>
        </div>

        <div class="actions-bar">
            <a href="{{ route('admin.usuarios.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Nuevo usuario</a>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchUsuario" placeholder="Buscar por nombre o email...">
            </div>
        </div>

        <div class="data-panel">
            <div class="panel-header"><h3>📋 Lista de usuarios</h3></div>

            <div class="table-responsive">
                <table class="data-table" id="tablaUsuarios">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th class="col-acciones">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios ?? [] as $usuario)
                        <tr data-nombre="{{ strtolower($usuario->nombre) }}" data-email="{{ strtolower($usuario->email) }}">
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->nombre }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->rol->nombre ?? 'N/A' }}</td>
                            <td>
                                <span class="estado-badge {{ $usuario->estado == 1 ? 'activo' : 'inactivo' }}">
                                    {{ $usuario->estado == 1 ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="acciones-cell">
                                <button class="btn-ver" onclick="verUsuario({{ $usuario->id }})"><i class="fas fa-eye"></i></button>
                                <button class="btn-editar" onclick="editarUsuario({{ $usuario->id }})"><i class="fas fa-edit"></i></button>
                                <button class="btn-eliminar" onclick="eliminarUsuario({{ $usuario->id }})"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center">No hay usuarios registrados</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
    document.getElementById('searchUsuario')?.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tablaUsuarios tbody tr');
        rows.forEach(row => {
            const nombre = row.getAttribute('data-nombre') || '';
            const email = row.getAttribute('data-email') || '';
            row.style.display = (nombre.includes(searchTerm) || email.includes(searchTerm)) ? '' : 'none';
        });
    });

    function verUsuario(id) { window.location.href = `/admin/usuarios/${id}`; }
    function editarUsuario(id) { window.location.href = `/admin/usuarios/${id}/edit`; }
    function eliminarUsuario(id) {
        if (confirm('¿Eliminar este usuario?')) {
            fetch(`/admin/usuarios/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(() => location.reload());
        }
    }
</script>

<style>
    .actions-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem; }
    .btn-primary { background: linear-gradient(135deg, #1a5fa8, #0d9e75); color: white; padding: 0.5rem 1rem; border-radius: 30px; text-decoration: none; }
    .search-box { display: flex; align-items: center; gap: 0.5rem; background: white; padding: 0.3rem 1rem; border-radius: 30px; }
    .search-box input { border: none; outline: none; width: 200px; }
    .estado-badge.activo { background: rgba(13,158,117,0.15); color: #0d9e75; }
    .estado-badge.inactivo { background: rgba(239,68,68,0.15); color: #ef4444; }
    .acciones-cell { text-align: center; }
    .btn-ver, .btn-editar, .btn-eliminar { background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; }
    .btn-ver { color: #1a5fa8; }
    .btn-editar { color: #f59e0b; }
    .btn-eliminar { color: #ef4444; }
</style>

</body>
</html>