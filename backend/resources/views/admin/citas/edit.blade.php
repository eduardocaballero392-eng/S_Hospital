<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Editar Cita</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-dash">

<div class="admin-layout">
    @include('admin.partials.sidebar')
    <main class="admin-main">
        <div class="admin-header"><h1>✏️ Editar Cita</h1></div>

        <form method="POST" action="{{ route('admin.citas.update', $cita->id) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Paciente</label>
                <select name="paciente_id" class="form-control">
                    @foreach($pacientes as $p)
                        <option value="{{ $p->id }}" {{ $cita->paciente_id == $p->id ? 'selected' : '' }}>{{ $p->nombre }} {{ $p->apellido }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Médico</label>
                <select name="medico_id" class="form-control">
                    @foreach($medicos as $m)
                        <option value="{{ $m->id }}" {{ $cita->medico_id == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Fecha y hora</label>
                <input type="datetime-local" name="fecha_hora" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($cita->fecha_hora)) }}" required>
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="estado" class="form-control">
                    <option value="pendiente" {{ $cita->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="confirmada" {{ $cita->estado == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                    <option value="completada" {{ $cita->estado == 'completada' ? 'selected' : '' }}>Completada</option>
                    <option value="cancelada" {{ $cita->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>
            <div class="form-group">
                <label>Motivo</label>
                <textarea name="motivo" class="form-control" rows="3">{{ $cita->motivo }}</textarea>
            </div>
            <button type="submit" class="btn-guardar">Guardar cambios</button>
            <a href="{{ route('admin.citas.index') }}" class="btn-cancelar">Cancelar</a>
        </form>
    </main>
</div>

<style>
    .form-group { margin-bottom: 1rem; }
    .form-control { width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 8px; }
    .btn-guardar { background: #0d9e75; color: white; border: none; padding: 0.5rem 1rem; border-radius: 8px; cursor: pointer; }
    .btn-cancelar { background: #f1f5f9; padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; color: #64748b; }
</style>

</body>
</html>