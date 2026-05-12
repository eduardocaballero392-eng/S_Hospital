<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Crear Paciente</title>
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
            <h1><i class="fas fa-user-plus"></i> Crear paciente</h1>
            <div class="admin-user">
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->nombre }}</div>
                    <div class="user-role">Administrador</div>
                </div>
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}</div>
            </div>
        </div>

        <div class="form-container">
            <form method="POST" action="{{ route('admin.pacientes.store') }}">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Nombres *</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                        @error('nombre') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Apellidos *</label>
                        <input type="text" name="apellido" class="form-control" value="{{ old('apellido') }}" required>
                        @error('apellido') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-id-card"></i> DNI *</label>
                        <input type="text" name="DNI" class="form-control" value="{{ old('DNI') }}" required>
                        @error('DNI') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> Teléfono *</label>
                        <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" required>
                        @error('telefono') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email *</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Fecha de nacimiento *</label>
                        <input type="date" name="fecha_nac" class="form-control" value="{{ old('fecha_nac') }}" required>
                        @error('fecha_nac') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-venus-mars"></i> Género *</label>
                        <select name="genero" class="form-control" required>
                            <option value="">Seleccione</option>
                            <option value="M" {{ old('genero') == 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('genero') == 'F' ? 'selected' : '' }}>Femenino</option>
                        </select>
                        @error('genero') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-user-md"></i> Médico asignado</label>
                        <select name="medico_asignado_id" class="form-control">
                            <option value="">-- Ninguno --</option>
                            @foreach($medicos ?? [] as $medico)
                                <option value="{{ $medico->id }}" {{ old('medico_asignado_id') == $medico->id ? 'selected' : '' }}>
                                    {{ $medico->nombre }} - {{ $medico->especialidad }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-map-marker-alt"></i> Dirección</label>
                    <textarea name="direccion" class="form-control" rows="3">{{ old('direccion') }}</textarea>
                    @error('direccion') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.pacientes.index') }}" class="btn-cancelar">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn-guardar">
                        <i class="fas fa-save"></i> Crear paciente
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<style>
    .form-container {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        max-width: 900px;
        margin: 0 auto;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
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
        width: 18px;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.85rem;
        font-family: 'Outfit', sans-serif;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #1a5fa8;
        box-shadow: 0 0 0 3px rgba(26, 95, 168, 0.1);
    }

    textarea.form-control {
        resize: vertical;
    }

    .error {
        font-size: 0.7rem;
        color: #ef4444;
        margin-top: 0.3rem;
        display: block;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }

    .btn-cancelar {
        background: #f1f5f9;
        color: #64748b;
        padding: 0.6rem 1.2rem;
        border-radius: 10px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-cancelar:hover {
        background: #e2e8f0;
    }

    .btn-guardar {
        background: linear-gradient(135deg, #1a5fa8, #0d9e75);
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-guardar:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26, 95, 168, 0.3);
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        .form-container {
            padding: 1.5rem;
        }
    }
</style>

</body>
</html>