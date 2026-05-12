<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Crear Médico</title>
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
            <h1>Crear médico</h1>
            <div class="admin-user">
                <div class="user-info">{{ Auth::user()->nombre }}</div>
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}</div>
            </div>
        </div>

        <div class="form-container">
            <form method="POST" action="{{ route('admin.medicos.store') }}">
                @csrf
                <div class="form-group">
                    <label>Nombre completo</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Especialidad</label>
                    <input type="text" name="especialidad" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" class="form-control" required>
                </div>
                <div class="form-actions">
                    <a href="{{ route('admin.medicos.index') }}" class="btn-cancelar">Cancelar</a>
                    <button type="submit" class="btn-guardar">Crear médico</button>
                </div>
            </form>
        </div>
    </main>
</div>

<style>
    .form-container { background: white; border-radius: 20px; padding: 2rem; max-width: 600px; margin: 0 auto; }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-weight: 600; margin-bottom: 0.5rem; }
    .form-control { width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 10px; }
    .form-actions { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem; }
    .btn-cancelar { background: #f1f5f9; padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; color: #64748b; }
    .btn-guardar { background: linear-gradient(135deg, #1a5fa8, #0d9e75); color: white; border: none; padding: 0.5rem 1.5rem; border-radius: 8px; cursor: pointer; }
</style>

</body>
</html>