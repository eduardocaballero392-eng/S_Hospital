<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Editar Diagnóstico</title>
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
            <a href="{{ route('medico.dashboard') }}" class="nav-item">Dashboard</a>
            <a href="{{ route('medico.citas') }}" class="nav-item">Mis citas</a>
            <a href="{{ route('medico.pacientes') }}" class="nav-item">Mis pacientes</a>
            <a href="{{ route('medico.recetas') }}" class="nav-item">Recetas</a>
            <a href="{{ route('medico.diagnosticos') }}" class="nav-item active">Diagnósticos</a>
            <a href="{{ route('medico.historial') }}" class="nav-item">Historial clínico</a>
            <a href="{{ route('medico.perfil') }}" class="nav-item">Mi perfil</a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Cerrar sesión</button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-edit"></i> Editar diagnóstico</h1>
            <p>Modifica la información del diagnóstico</p>
        </div>

        <div class="form-container">
            <form method="POST" action="{{ route('medico.diagnosticos.update', $diagnostico->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label><i class="fas fa-user"></i> Paciente</label>
                    <select name="paciente_id" class="form-control" required>
                        <option value="">Seleccione un paciente</option>
                        @foreach($pacientes as $paciente)
                            <option value="{{ $paciente->id }}" {{ $diagnostico->paciente_id == $paciente->id ? 'selected' : '' }}>
                                {{ $paciente->nombre }} {{ $paciente->apellido ?? '' }} - {{ $paciente->DNI ?? 'Sin DNI' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-stethoscope"></i> Nombre del diagnóstico</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $diagnostico->nombre) }}" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-calendar-alt"></i> Fecha del diagnóstico</label>
                    <input type="date" name="fecha_diagnostico" class="form-control" value="{{ old('fecha_diagnostico', $diagnostico->fecha_diagnostico) }}" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="5" required>{{ old('descripcion', $diagnostico->descripcion) }}</textarea>
                </div>

                <div class="form-buttons">
                    <a href="{{ route('medico.diagnosticos') }}" class="btn-cancelar">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn-guardar">
                        <i class="fas fa-save"></i> Actualizar diagnóstico
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
        max-width: 800px;
        margin: 0 auto;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #1a2a3a;
        margin-bottom: 0.5rem;
    }
    
    .form-group label i {
        color: #1a5fa8;
        margin-right: 8px;
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
    
    .form-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
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
        padding: 0.6rem 1.2rem;
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
</style>

</body>
</html>