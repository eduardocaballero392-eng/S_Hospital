<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Ver Cita</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-dash">

<div class="admin-layout">
    @include('admin.partials.sidebar')
    <main class="admin-main">
        <div class="admin-header">
            <h1>📅 Detalle de Cita</h1>
            <a href="{{ route('admin.citas.index') }}" class="btn-volver">← Volver</a>
        </div>
        <div class="detail-card">
            <div class="detail-row"><strong>ID:</strong> {{ $cita->id }}</div>
            <div class="detail-row"><strong>Paciente:</strong> {{ $cita->paciente->nombre ?? 'N/A' }} {{ $cita->paciente->apellido ?? '' }}</div>
            <div class="detail-row"><strong>Médico:</strong> {{ $cita->medico->nombre ?? 'No asignado' }}</div>
            <div class="detail-row"><strong>Fecha y hora:</strong> {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y H:i') }}</div>
            <div class="detail-row"><strong>Estado:</strong> <span class="estado-badge {{ $cita->estado }}">{{ ucfirst($cita->estado) }}</span></div>
            <div class="detail-row"><strong>Motivo:</strong> {{ $cita->motivo ?? 'No especificado' }}</div>
        </div>
    </main>
</div>

<style>
    .detail-card { background: white; border-radius: 20px; padding: 1.5rem; max-width: 600px; margin: 0 auto; }
    .detail-row { padding: 0.5rem 0; border-bottom: 1px solid #e2e8f0; }
    .btn-volver { background: #f1f5f9; padding: 0.3rem 1rem; border-radius: 20px; text-decoration: none; color: #64748b; }
</style>

</body>
</html>