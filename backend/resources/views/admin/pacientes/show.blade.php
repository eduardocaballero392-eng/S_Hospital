<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Detalle Paciente</title>
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
            <h1><i class="fas fa-user-circle"></i> Detalle del paciente</h1>
            <div class="admin-user">
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->nombre }}</div>
                    <div class="user-role">Administrador</div>
                </div>
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}</div>
            </div>
        </div>

        <div class="detail-container">
            {{-- CABECERA DEL PACIENTE --}}
            <div class="detail-header">
                <div class="patient-avatar">
                    <div class="avatar-large">{{ strtoupper(substr($paciente->nombre, 0, 1)) }}{{ strtoupper(substr($paciente->apellido ?? '', 0, 1)) }}</div>
                </div>
                <div class="patient-title">
                    <h2>{{ $paciente->nombre }} {{ $paciente->apellido }}</h2>
                    <div class="patient-badges">
                        <span class="badge {{ $paciente->usuario && $paciente->usuario->estado == 1 ? 'active' : 'inactive' }}">
                            {{ $paciente->usuario && $paciente->usuario->estado == 1 ? 'Activo' : 'Inactivo' }}
                        </span>
                        <span class="badge">{{ $paciente->genero == 'M' ? 'Masculino' : 'Femenino' }}</span>
                        <span class="badge">{{ $paciente->edad ?? '?' }} años</span>
                    </div>
                </div>
                <div class="patient-actions-header">
                    <a href="{{ route('admin.pacientes.edit', $paciente->id) }}" class="btn-edit">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('admin.pacientes.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            {{-- INFORMACIÓN DEL PACIENTE --}}
            <div class="detail-grid">
                <div class="detail-card">
                    <h3><i class="fas fa-id-card"></i> Información personal</h3>
                    <div class="detail-row">
                        <div class="detail-label">DNI:</div>
                        <div class="detail-value">{{ $paciente->DNI ?? 'N/A' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Nombres:</div>
                        <div class="detail-value">{{ $paciente->nombre }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Apellidos:</div>
                        <div class="detail-value">{{ $paciente->apellido ?? 'N/A' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Fecha de nacimiento:</div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($paciente->fecha_nac)->format('d/m/Y') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Edad:</div>
                        <div class="detail-value">{{ $paciente->edad ?? '?' }} años</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Género:</div>
                        <div class="detail-value">{{ $paciente->genero == 'M' ? 'Masculino' : 'Femenino' }}</div>
                    </div>
                </div>

                <div class="detail-card">
                    <h3><i class="fas fa-address-card"></i> Información de contacto</h3>
                    <div class="detail-row">
                        <div class="detail-label">Teléfono:</div>
                        <div class="detail-value">{{ $paciente->telefono ?? 'N/A' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Email:</div>
                        <div class="detail-value">{{ $paciente->email ?? $paciente->usuario->email ?? 'N/A' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Dirección:</div>
                        <div class="detail-value">{{ $paciente->direccion ?? 'No registrada' }}</div>
                    </div>
                </div>

                <div class="detail-card">
                    <h3><i class="fas fa-user-md"></i> Asignación médica</h3>
                    <div class="detail-row">
                        <div class="detail-label">Médico asignado:</div>
                        <div class="detail-value">
                            @if($paciente->medicoAsignado)
                                <strong>{{ $paciente->medicoAsignado->nombre }}</strong><br>
                                <small>{{ $paciente->medicoAsignado->especialidad }}</small>
                            @else
                                <span class="sin-asignar">No tiene médico asignado</span>
                            @endif
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Cuenta usuario:</div>
                        <div class="detail-value">
                            @if($paciente->usuario)
                                Activa desde: {{ \Carbon\Carbon::parse($paciente->usuario->created_at)->format('d/m/Y') }}
                            @else
                                <span class="sin-asignar">Sin cuenta asociada</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- CITAS DEL PACIENTE --}}
            <div class="detail-card full-width">
                <h3><i class="fas fa-calendar-alt"></i> Historial de citas</h3>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Médico</th>
                                <th>Especialidad</th>
                                <th>Estado</th>
                            </thead>
                        <tbody>
                            @forelse($paciente->citas ?? [] as $cita)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y H:i') }}
                                <td>{{ $cita->medico->nombre ?? 'N/A' }}
                                <td>{{ $cita->medico->especialidad ?? 'N/A' }}
                                <td><span class="badge {{ $cita->estado }}">{{ ucfirst($cita->estado) }}</span>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="empty-row">No hay citas registradas</td></table>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- DIAGNÓSTICOS DEL PACIENTE --}}
            <div class="detail-card full-width">
                <h3><i class="fas fa-stethoscope"></i> Historial de diagnósticos</h3>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Diagnóstico</th>
                                <th>Médico</th>
                                <th>Descripción</th>
                            </thead>
                        <tbody>
                            @forelse($paciente->diagnosticos ?? [] as $diagnostico)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($diagnostico->fecha_diagnostico)->format('d/m/Y') }}
                                <td><strong>{{ $diagnostico->nombre }}</strong>
                                <td>{{ $diagnostico->medico->nombre ?? 'N/A' }}
                                <td>{{ Str::limit($diagnostico->descripcion, 50) }}
                            </tr>
                            @empty
                            <tr><td colspan="4" class="empty-row">No hay diagnósticos registrados</td></tr>
                            @endforelse                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
    .detail-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .detail-header {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    .patient-avatar {
        .avatar-large {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #1a5fa8, #0d9e75);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 2rem;
        }
    }

    .patient-title {
        flex: 1;

        h2 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a2a3a;
            margin-bottom: 0.3rem;
        }

        .patient-badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;

            .badge {
                padding: 3px 10px;
                border-radius: 30px;
                font-size: 0.7rem;
                font-weight: 600;
            }
        }
    }

    .patient-actions-header {
        display: flex;
        gap: 0.8rem;
    }

    .btn-edit, .btn-back {
        padding: 0.5rem 1rem;
        border-radius: 30px;
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-edit {
        background: #0d9e75;
        color: white;
    }

    .btn-back {
        background: #f1f5f9;
        color: #64748b;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .detail-card {
        background: white;
        border-radius: 20px;
        padding: 1.2rem;

        &.full-width {
            grid-column: span 3;
            margin-bottom: 1.5rem;
        }

        h3 {
            font-size: 0.9rem;
            font-weight: 600;
            color: #1a2a3a;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e2e8f0;

            i {
                color: #1a5fa8;
                margin-right: 6px;
            }
        }

        .detail-row {
            display: flex;
            margin-bottom: 0.8rem;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid #f0f2f8;

            .detail-label {
                width: 130px;
                font-weight: 600;
                color: #1a2a3a;
                font-size: 0.8rem;
            }

            .detail-value {
                flex: 1;
                color: #64748b;
                font-size: 0.8rem;
            }
        }
    }

    .sin-asignar {
        color: #f59e0b;
        font-style: italic;
    }

    @media (max-width: 900px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
        .detail-card.full-width {
            grid-column: span 1;
        }
        .detail-header {
            flex-direction: column;
            text-align: center;
        }
        .patient-actions-header {
            width: 100%;
            justify-content: center;
        }
    }
</style>

</body>
</html>