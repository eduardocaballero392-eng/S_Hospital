<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Editar Usuario</title>
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
            <h1><i class="fas fa-user-edit"></i> Editar Usuario</h1>
            <div class="admin-user">
                <div class="user-info">{{ Auth::user()->nombre }}</div>
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}</div>
            </div>
        </div>

        <div class="form-container">
            <form method="POST" action="{{ route('admin.usuarios.update', $usuario->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label><i class="fas fa-user"></i> Nombre completo *</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $usuario->nombre) }}" required>
                    @error('nombre') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
                    @error('email') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Nueva contraseña (dejar en blanco para no cambiar)</label>
                    <input type="password" name="contrasena" class="form-control">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Rol *</label>
                    <select name="rol_id" class="form-control" required>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->id }}" {{ old('rol_id', $usuario->rol_id) == $rol->id ? 'selected' : '' }}>
                                {{ $rol->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('rol_id') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label><i class="fas fa-toggle-on"></i> Estado *</label>
                    <select name="estado" class="form-control" required>
                        <option value="1" {{ old('estado', $usuario->estado) == 1 ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('estado', $usuario->estado) == 0 ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.usuarios.index') }}" class="btn-cancelar">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn-guardar">
                        <i class="fas fa-save"></i> Actualizar usuario
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<style>
    .form-container { background: white; border-radius: 20px; padding: 2rem; max-width: 600px; margin: 0 auto; }
    .form-group { margin-bottom: 1.2rem; }
    .form-group label { display: block; font-weight: 600; margin-bottom: 0.5rem; color: #1a2a3a; }
    .form-group label i { color: #1a5fa8; width: 20px; }
    .form-control { width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 10px; font-family: 'Outfit', sans-serif; }
    .form-control:focus { outline: none; border-color: #1a5fa8; }
    .error { color: #ef4444; font-size: 0.7rem; margin-top: 0.3rem; display: block; }
    .form-actions { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem; }
    .btn-cancelar { background: #f1f5f9; padding: 0.6rem 1.2rem; border-radius: 10px; text-decoration: none; color: #64748b; }
    .btn-guardar { background: linear-gradient(135deg, #1a5fa8, #0d9e75); color: white; border: none; padding: 0.6rem 1.5rem; border-radius: 10px; cursor: pointer; }
</style>

</body>
</html>