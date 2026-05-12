<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Ver Reclamación</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-dash">

<div class="admin-layout">
    @include('admin.partials.sidebar')

    <main class="admin-main">
        <div class="admin-header">
            <h1><i class="fas fa-file-alt"></i> Detalle de Reclamación</h1>
            <a href="{{ route('admin.reclamaciones.index') }}" class="btn-volver">← Volver</a>
        </div>

        <div class="detail-card">
            <div class="detail-row"><strong>ID:</strong> {{ $reclamacion->id }}</div>
            <div class="detail-row"><strong>Nombre:</strong> {{ $reclamacion->nombre }} {{ $reclamacion->apellido }}</div>
            <div class="detail-row"><strong>Tipo Documento:</strong> {{ $reclamacion->tipo_documento }}</div>
            <div class="detail-row"><strong>N° Documento:</strong> {{ $reclamacion->nro_documento }}</div>
            <div class="detail-row"><strong>Email:</strong> {{ $reclamacion->email }}</div>
            <div class="detail-row"><strong>Teléfono:</strong> {{ $reclamacion->telefono }}</div>
            <div class="detail-row"><strong>Dirección:</strong> {{ $reclamacion->direccion }}</div>
            <div class="detail-row"><strong>Tipo Reclamo:</strong> {{ $reclamacion->tipo_reclamo }}</div>
            <div class="detail-row"><strong>Detalle:</strong> {{ $reclamacion->detalle }}</div>
        </div>
    </main>
</div>

<style>
    .detail-card { background: white; border-radius: 20px; padding: 1.5rem; max-width: 700px; margin: 0 auto; }
    .detail-row { padding: 0.5rem 0; border-bottom: 1px solid #e2e8f0; }
    .btn-volver { background: #f1f5f9; padding: 0.3rem 1rem; border-radius: 20px; text-decoration: none; color: #64748b; }
</style>

</body>
</html>
