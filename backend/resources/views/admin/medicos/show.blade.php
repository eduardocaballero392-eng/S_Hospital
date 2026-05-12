<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Ver Médico</title>
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
            <h1><i class="fas fa-user-md"></i> Detalle del Médico</h1>
            <div class="admin-user">
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->nombre }}</div>
                    <div class="user-role">Administrador</div>
                </div>
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}</div>
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-header">
                <div class="detail-avatar">{{ strtoupper(substr($medico->nombre, 0, 1)) }}</div>
                <div class="detail-title">
                    <h2>{{ $medico->nombre }}</h2>
                    <p>{{ $medico->especialidad }}</p>
                </div>
                <div class="detail-actions">
                    <a href="{{ route('admin.medicos.edit', $medico->id) }}" class="btn-editar">✏️ Editar</a>
                    <a href="{{ route('admin.medicos.index') }}" class="btn-volver">← Volver</a>
                </div>
            </div>

            <div class="detail-info">
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-id-card"></i> ID:</div>
                    <div class="info-value">{{ $medico->id }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-user"></i> Nombre:</div>
                    <div class="info-value">{{ $medico->nombre }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-stethoscope"></i> Especialidad:</div>
                    <div class="info-value">{{ $medico->especialidad }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-phone"></i> Teléfono:</div>
                    <div class="info-value">{{ $medico->telefono ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-envelope"></i> Email:</div>
                    <div class="info-value">{{ $medico->email ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-toggle-on"></i> Estado:</div>
                    <div class="info-value">
                        @php $activo = $medico->usuario?->isEstadoActivo(); @endphp
                        <span class="estado-badge {{ $activo ? 'activo' : 'inactivo' }}">
                            {{ $activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
    .detail-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        max-width: 700px;
        margin: 0 auto;
    }
    .detail-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e2e8f0;
        flex-wrap: wrap;
    }
    .detail-avatar {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #1a5fa8, #0d9e75);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 700;
        color: white;
    }
    .detail-title h2 {
        font-size: 1.3rem;
        margin-bottom: 0.2rem;
        color: #1a2a3a;
    }
    .detail-title p {
        color: #64748b;
        font-size: 0.85rem;
    }
    .detail-actions {
        margin-left: auto;
        display: flex;
        gap: 1rem;
    }
    .btn-editar, .btn-volver {
        padding: 0.5rem 1rem;
        border-radius: 30px;
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .btn-editar {
        background: #0d9e75;
        color: white;
    }
    .btn-volver {
        background: #f1f5f9;
        color: #64748b;
    }
    .detail-info {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .info-row {
        display: flex;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f0f2f8;
    }
    .info-label {
        width: 130px;
        font-weight: 600;
        color: #1a2a3a;
        font-size: 0.85rem;
    }
    .info-label i {
        color: #1a5fa8;
        width: 20px;
    }
    .info-value {
        flex: 1;
        color: #64748b;
        font-size: 0.85rem;
    }
    .estado-badge {
        display: inline-block;
        padding: 3px 12px;
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
    @media (max-width: 600px) {
        .detail-header {
            flex-direction: column;
            text-align: center;
        }
        .detail-actions {
            margin-left: 0;
        }
        .info-row {
            flex-direction: column;
            gap: 0.3rem;
        }
        .info-label {
            width: 100%;
        }
    }
</style>

</body>
</html>