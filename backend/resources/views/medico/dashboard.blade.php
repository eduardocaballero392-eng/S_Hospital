<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Panel Médico</title>
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
            <a href="{{ route('medico.dashboard') }}" class="nav-item active">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('medico.citas') }}" class="nav-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Mis citas</span>
            </a>
            <a href="{{ route('medico.pacientes') }}" class="nav-item">
                <i class="fas fa-users"></i>
                <span>Mis pacientes</span>
            </a>
          
            <a href="{{ route('medico.diagnosticos') }}" class="nav-item">
                <i class="fas fa-file-alt"></i>
                <span>Diagnósticos</span>
            </a>
            <a href="{{ route('medico.historial') }}" class="nav-item">
                <i class="fas fa-history"></i>
                <span>Historial clínico</span>
            </a>
            
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar sesión</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="main-content">
        {{-- HEADER MÉDICO --}}
        <div class="medico-header">
            <div class="medico-info">
                <div class="medico-avatar">{{ strtoupper(substr($usuario->nombre ?? 'D', 0, 1)) }}</div>
                <div class="medico-datos">
                    <h1>Dr. {{ $usuario->nombre ?? 'Ramírez López' }}</h1>
                    <span class="medico-especialidad">{{ $medico?->especialidad?->nombre ?? 'MÉDICO' }}</span>
                </div>
            </div>
            <div class="medico-fecha">
                <i class="fas fa-calendar-alt"></i>
                <span>{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</span>
            </div>
        </div>

        {{-- WELCOME BANNER --}}
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>¡Hola, {{ $usuario->nombre ?? 'Dr. Ramírez' }}! </h1>
                <p>{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
            </div>
            <div class="welcome-stats">
                <div class="stat-card">
                    <div class="stat-number">{{ isset($citasHoy) ? count($citasHoy) : 0 }}</div>
                    <div class="stat-label">Citas hoy</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ isset($citasPendientes) ? count($citasPendientes) : 0 }}</div>
                    <div class="stat-label">Pendientes</div>
                </div>
            </div>
        </div>

        {{-- STATS GRID --}}
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                <div>
                    <div class="stat-number">{{ isset($citasHoy) ? count($citasHoy) : 0 }}</div>
                    <div class="stat-label">Citas hoy</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="stat-number">{{ isset($citasPendientes) ? count($citasPendientes) : 0 }}</div>
                    <div class="stat-label">Pendientes</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div>
                    <div class="stat-number">{{ $totalPacientes ?? 0 }}</div>
                    <div class="stat-label">Pacientes atendidos</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-stethoscope"></i></div>
                <div>
                    <div class="stat-number">{{ $medico?->especialidad?->nombre ?? 'Medicina General' }}</div>
                    <div class="stat-label">Especialidad</div>
                </div>
            </div>
        </div>

        {{-- TWO COLUMNS GRID --}}
        <div class="two-columns-grid">
            {{-- PACIENTES PROGRAMADOS --}}
            <div class="panel-pacientes">
                <div class="panel-header">
                    <div class="panel-title">
                        <i class="fas fa-users"></i> Pacientes programados
                        <span class="fecha-actual">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM') }}</span>
                    </div>
                    <div class="buscador-paciente">
                        <i class="fas fa-search"></i>
                        <input type="text" id="buscadorPacientes" placeholder="Buscar por nombre o DNI...">
                    </div>
                </div>

                <div class="lista-pacientes-container">
                    @if(!isset($citasHoy) || count($citasHoy) === 0)
                        <div class="empty-pacientes">
                            <i class="fas fa-calendar-day"></i>
                            <p>No hay citas programadas para hoy</p>
                            <span>Tu agenda está libre</span>
                        </div>
                    @else
                        @foreach($citasHoy as $index => $cita)
                        <div class="paciente-card" data-nombre="{{ strtolower($cita->paciente->nombre ?? '') }} {{ strtolower($cita->paciente->apellido ?? '') }}" data-dni="{{ $cita->paciente->DNI ?? '' }}">
                            <div class="paciente-hora">
                                <div class="hora-main">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i') }}</div>
                                @if($index == 0 && $cita->estado == 'pendiente')
                                    <span class="estado-ahora">AHORA</span>
                                @endif
                            </div>
                            <div class="paciente-info">
                                <div class="paciente-nombre">{{ $cita->paciente->nombre ?? 'Paciente' }} {{ $cita->paciente->apellido ?? '' }}</div>
                                <div class="paciente-detalles">
                                    <span><i class="fas fa-id-card"></i> {{ $cita->paciente->DNI ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <button class="btn-atender" onclick="atenderPaciente({{ $cita->id }}, '{{ addslashes($cita->paciente->nombre ?? 'Paciente') }}')">
                                <i class="fas fa-user-md"></i> Atender
                            </button>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- TIEMPOS DE ESPERA --}}
            <div class="panel-espera">
                <div class="panel-header">
                    <div class="panel-title">
                        <i class="fas fa-clock"></i> Estado actual de consulta
                    </div>
                    <div class="refresh-btn" onclick="actualizarEstado()">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                </div>

                <div class="paciente-actual-card">
                    <div class="paciente-actual-header">ATENDIENDO AHORA</div>
                    @php
                        $citaActualNombre = 'Ninguno';
                        $citaActualHora = '--:--';
                        if(isset($citasHoy) && !empty($citasHoy)) {
                            foreach($citasHoy as $cita) {
                                if($cita->estado == 'atendiendo') {
                                    $citaActualNombre = ($cita->paciente->nombre ?? 'Paciente') . ' ' . ($cita->paciente->apellido ?? '');
                                    $citaActualHora = \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i');
                                    break;
                                }
                            }
                        }
                    @endphp
                    <div class="actual-nombre">{{ $citaActualNombre }}</div>
                    <div class="actual-hora">{{ $citaActualHora }}</div>
                    <div class="tiempo-transcurrido">Tiempo en consulta: <span id="tiempoActual">00:00</span></div>
                </div>

                <div class="lista-espera-card">
                    <div class="lista-espera-header">
                        <span>PACIENTES EN ESPERA</span>
                        <span class="contador-espera" id="contadorEspera">0</span>
                    </div>
                    @php
                        $contador = 1;
                        $citasEnEspera = [];
                        if(isset($citasHoy) && !empty($citasHoy)) {
                            foreach($citasHoy as $cita) {
                                if($cita->estado == 'pendiente') {
                                    $citasEnEspera[] = $cita;
                                }
                            }
                        }
                    @endphp
                    @foreach($citasEnEspera as $cita)
                        <div class="espera-item">
                            <div class="espera-numero">{{ $contador++ }}</div>
                            <div class="espera-info">
                                <div class="espera-nombre">{{ $cita->paciente->nombre ?? 'Paciente' }} {{ $cita->paciente->apellido ?? '' }}</div>
                                <div class="espera-hora">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i') }}</div>
                            </div>
                            <div class="espera-tiempo">Espera: <span id="espera-{{ $cita->id }}">calculando...</span></div>
                        </div>
                    @endforeach
                    @if(empty($citasEnEspera))
                        <div class="no-espera">No hay pacientes en espera</div>
                    @endif
                </div>

                <div class="estimacion-card">
                    <i class="fas fa-chart-line"></i>
                    <div class="estimacion-info">
                        <div class="estimacion-label">Tiempo estimado de espera</div>
                        <div class="estimacion-valor" id="estimacionEspera">
                            @php $enEspera = isset($citasEnEspera) ? count($citasEnEspera) : 0; @endphp
                            @if($enEspera > 0) ~{{ $enEspera * 15 }} minutos @else Sin espera @endif
                        </div>
                    </div>
                </div>

                <div class="control-buttons">
                    <button class="btn-iniciar-consulta" onclick="iniciarConsulta()">
                        <i class="fas fa-play"></i> Iniciar consulta
                    </button>
                    <button class="btn-finalizar-consulta" onclick="finalizarConsulta()">
                        <i class="fas fa-check"></i> Finalizar
                    </button>
                    <button class="btn-llamar" onclick="llamarSiguiente()">
                        <i class="fas fa-bell"></i> Llamar siguiente
                    </button>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    let tiempoInicioConsulta = null;
    let timerInterval = null;

    function filtrarPacientes() {
        const input = document.getElementById('buscadorPacientes');
        if (!input) return;
        const filter = input.value.toLowerCase();
        const cards = document.querySelectorAll('.paciente-card');
        cards.forEach(card => {
            const nombre = card.getAttribute('data-nombre') || '';
            const dni = card.getAttribute('data-dni') || '';
            card.style.display = (nombre.includes(filter) || dni.includes(filter)) ? 'flex' : 'none';
        });
    }

    function atenderPaciente(citaId, pacienteNombre) {
        if (confirm(`¿Iniciar consulta con ${pacienteNombre}?`)) {
            fetch(`/medico/citas/${citaId}/atender`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
            .then(response => response.json()).then(data => {
                if (data.success) { mostrarNotificacion(`Consulta iniciada con ${pacienteNombre}`, 'success'); iniciarTimerConsulta(); setTimeout(() => location.reload(), 1000); }
                else { mostrarNotificacion('Error al iniciar la consulta', 'error'); }
            }).catch(() => mostrarNotificacion('Error al conectar con el servidor', 'error'));
        }
    }

    function iniciarConsulta() {
        const primerBoton = document.querySelector('.paciente-card:not([style*="display: none"]) .btn-atender');
        if (primerBoton) primerBoton.click();
        else mostrarNotificacion('No hay pacientes pendientes para atender', 'info');
    }

    function finalizarConsulta() {
        if (confirm('¿Finalizar la consulta actual?')) {
            fetch('/medico/citas/finalizar', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
            .then(response => response.json()).then(data => {
                if (data.success) { detenerTimerConsulta(); mostrarNotificacion('Consulta finalizada correctamente', 'success'); setTimeout(() => location.reload(), 1000); }
                else { mostrarNotificacion('Error al finalizar la consulta', 'error'); }
            }).catch(() => mostrarNotificacion('Error al conectar con el servidor', 'error'));
        }
    }

    function llamarSiguiente() {
        const esperaItems = document.querySelectorAll('.espera-item');
        if (esperaItems.length > 0) {
            const siguiente = esperaItems[0];
            const nombre = siguiente.querySelector('.espera-nombre')?.innerText || 'Paciente';
            mostrarNotificacion(`🔔 Llamando a: ${nombre}`, 'success');
        } else { mostrarNotificacion('No hay pacientes en espera', 'info'); }
    }

    function actualizarEstado() { location.reload(); }

    function mostrarNotificacion(mensaje, tipo = 'info') {
        const notificacion = document.createElement('div');
        notificacion.className = `notificacion-flotante ${tipo}`;
        notificacion.innerHTML = `<i class="fas ${tipo === 'success' ? 'fa-check-circle' : tipo === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i><span>${mensaje}</span>`;
        document.body.appendChild(notificacion);
        setTimeout(() => notificacion.classList.add('show'), 10);
        setTimeout(() => { notificacion.classList.remove('show'); setTimeout(() => notificacion.remove(), 300); }, 3000);
    }

    function iniciarTimerConsulta() {
        if (timerInterval) clearInterval(timerInterval);
        tiempoInicioConsulta = new Date();
        const tiempoSpan = document.getElementById('tiempoActual');
        if (tiempoSpan) {
            timerInterval = setInterval(() => {
                const ahora = new Date();
                const diff = Math.floor((ahora - tiempoInicioConsulta) / 1000);
                const minutos = Math.floor(diff / 60);
                const segundos = diff % 60;
                tiempoSpan.innerText = `${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
            }, 1000);
        }
    }

    function detenerTimerConsulta() { if (timerInterval) { clearInterval(timerInterval); timerInterval = null; } }

    function actualizarTiemposEspera() {
        const esperaItems = document.querySelectorAll('.espera-item');
        esperaItems.forEach((item, index) => {
            const tiempoSpan = item.querySelector('.espera-tiempo span');
            if (tiempoSpan) tiempoSpan.innerText = `${(index + 1) * 15} min aprox`;
        });
        const contadorSpan = document.getElementById('contadorEspera');
        if (contadorSpan) contadorSpan.innerText = esperaItems.length;
        const estimacionSpan = document.getElementById('estimacionEspera');
        if (estimacionSpan) estimacionSpan.innerText = esperaItems.length > 0 ? `~${esperaItems.length * 15} minutos` : 'Sin espera';
    }

    document.getElementById('buscadorPacientes')?.addEventListener('keyup', filtrarPacientes);
    actualizarTiemposEspera();
    setInterval(actualizarTiemposEspera, 30000);
    const pacienteActualNombre = document.querySelector('.paciente-actual-card .actual-nombre')?.innerText;
    if (pacienteActualNombre && pacienteActualNombre !== 'Ninguno') iniciarTimerConsulta();
</script>

<style>
    .notificacion-flotante {
        position: fixed; top: 20px; right: 20px; background: white; border-radius: 12px; padding: 12px 20px;
        display: flex; align-items: center; gap: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); z-index: 1000;
        transform: translateX(400px); transition: transform 0.3s ease; font-family: 'Outfit', sans-serif;
    }
    .notificacion-flotante.show { transform: translateX(0); }
    .notificacion-flotante.success { border-left: 4px solid #0d9e75; }
    .notificacion-flotante.success i { color: #0d9e75; }
    .notificacion-flotante.info { border-left: 4px solid #1a5fa8; }
    .notificacion-flotante.info i { color: #1a5fa8; }
    .notificacion-flotante.error { border-left: 4px solid #ef4444; }
    .notificacion-flotante.error i { color: #ef4444; }
</style>

</body>
</html>