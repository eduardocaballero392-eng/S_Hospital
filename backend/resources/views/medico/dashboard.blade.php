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

    {{-- ============================== NAVBAR ============================== --}}
    <nav class="navbar-medico">
        <div class="nav-container">
            <div class="logo">
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
            <div class="nav-links">
                <a href="{{ route('medico.dashboard') }}" class="active">Dashboard</a>
                <a href="{{ route('medico.citas') }}">Citas</a>
                <a href="{{ route('medico.pacientes') }}">Pacientes</a>
                <a href="{{ route('medico.recetas') }}">Recetas</a>
            </div>
            <div class="user-area">
                <div class="user-info">
                    <div class="user-name">{{ $usuario->nombre ?? 'Dr. Ramírez' }}</div>
                    <div class="user-role">Médico</div>
                </div>
                <div class="avatar">{{ strtoupper(substr($usuario->nombre ?? 'D', 0, 1)) }}</div>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- ============================== MAIN CONTENT ============================== --}}
    <div class="main-medico">
        <div class="content-wrapper">

            {{-- BANNER DE BIENVENIDA --}}
            <div class="welcome-banner">
                <div class="welcome-text">
                    <h1>¡Hola, {{ $usuario->nombre ?? 'Dr. Ramírez' }}! 👋</h1>
                    <p>{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
                </div>
                <div class="welcome-stats">
                    <div class="stat-card">
                        <div class="stat-number">{{ count($citasHoy ?? []) }}</div>
                        <div class="stat-label">Citas hoy</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ count($citasPendientes ?? []) }}</div>
                        <div class="stat-label">Pendientes</div>
                    </div>
                </div>
            </div>

            {{-- STATS GRID --}}
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <div class="stat-number">{{ count($citasHoy ?? []) }}</div>
                        <div class="stat-label">Citas hoy</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="stat-number">{{ count($citasPendientes ?? []) }}</div>
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
                        <div class="stat-number">{{ $medico->especialidad->nombre ?? 'Medicina General' }}</div>
                        <div class="stat-label">Especialidad</div>
                    </div>
                </div>
            </div>

            {{-- ACCIONES RÁPIDAS --}}
            <div class="section-header">
                <h2><i class="fas fa-bolt"></i> Acciones rápidas</h2>
            </div>
            <div class="actions-grid">
                <a href="{{ route('medico.citas') }}" class="action-card">
                    <div class="action-icon aci-blue"><i class="fas fa-calendar-alt"></i></div>
                    <div class="action-info">
                        <div class="action-title">Mis citas</div>
                        <div class="action-sub">Ver y gestionar citas</div>
                    </div>
                    <div class="action-arrow"><i class="fas fa-arrow-right"></i></div>
                </a>
                <a href="{{ route('medico.pacientes') }}" class="action-card">
                    <div class="action-icon aci-green"><i class="fas fa-users"></i></div>
                    <div class="action-info">
                        <div class="action-title">Mis pacientes</div>
                        <div class="action-sub">Historial de pacientes</div>
                    </div>
                    <div class="action-arrow"><i class="fas fa-arrow-right"></i></div>
                </a>
                <a href="{{ route('medico.recetas') }}" class="action-card">
                    <div class="action-icon aci-amber"><i class="fas fa-prescription-bottle"></i></div>
                    <div class="action-info">
                        <div class="action-title">Recetas</div>
                        <div class="action-sub">Emitir y ver recetas</div>
                    </div>
                    <div class="action-arrow"><i class="fas fa-arrow-right"></i></div>
                </a>
                <a href="{{ route('medico.diagnosticos') }}" class="action-card">
                    <div class="action-icon aci-purple"><i class="fas fa-file-alt"></i></div>
                    <div class="action-info">
                        <div class="action-title">Diagnósticos</div>
                        <div class="action-sub">Registrar diagnósticos</div>
                    </div>
                    <div class="action-arrow"><i class="fas fa-arrow-right"></i></div>
                </a>
                <a href="{{ route('medico.historial') }}" class="action-card">
                    <div class="action-icon aci-coral"><i class="fas fa-history"></i></div>
                    <div class="action-info">
                        <div class="action-title">Historial clínico</div>
                        <div class="action-sub">Ver historiales completos</div>
                    </div>
                    <div class="action-arrow"><i class="fas fa-arrow-right"></i></div>
                </a>
                <a href="{{ route('medico.perfil') }}" class="action-card">
                    <div class="action-icon aci-teal"><i class="fas fa-user-md"></i></div>
                    <div class="action-info">
                        <div class="action-title">Mi perfil</div>
                        <div class="action-sub">Actualizar mis datos</div>
                    </div>
                    <div class="action-arrow"><i class="fas fa-arrow-right"></i></div>
                </a>
            </div>

            {{-- BOTTOM ROW: CITAS Y CONSEJOS --}}
            <div class="bottom-row">
                {{-- PRÓXIMAS CITAS --}}
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title"><i class="fas fa-calendar-week"></i> Próximas citas</div>
                        <a href="{{ route('medico.citas') }}" class="panel-link">Ver todas →</a>
                    </div>
                    @if(count($citasPendientes ?? []) === 0)
                        <div class="empty-state">
                            <i class="fas fa-calendar-check"></i>
                            <p>No tienes citas pendientes</p>
                            <span>Tu agenda está libre</span>
                        </div>
                    @else
                        @foreach($citasPendientes as $cita)
                        <div class="cita-card">
                            <div class="cita-time">
                                <div class="cita-hour">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i') }}</div>
                                <div class="cita-date">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d M') }}</div>
                            </div>
                            <div class="cita-info">
                                <div class="cita-patient">{{ $cita->paciente->nombre ?? 'Paciente' }} {{ $cita->paciente->apellido ?? '' }}</div>
                                <div class="cita-type">{{ $cita->tipo ?? 'Consulta general' }}</div>
                            </div>
                            <div class="cita-status {{ $cita->estado }}">
                                <span>{{ ucfirst($cita->estado ?? 'pendiente') }}</span>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>

                {{-- CONSEJOS Y RECORDATORIOS --}}
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title"><i class="fas fa-lightbulb"></i> Recordatorios</div>
                    </div>
                    <div class="tips-list">
                        <div class="tip-item">
                            <div class="tip-icon blue"><i class="fas fa-notes-medical"></i></div>
                            <div class="tip-content">
                                <div class="tip-title">Actualiza los diagnósticos</div>
                                <div class="tip-desc">Registra los diagnósticos después de cada consulta</div>
                            </div>
                        </div>
                        <div class="tip-item">
                            <div class="tip-icon green"><i class="fas fa-prescription"></i></div>
                            <div class="tip-content">
                                <div class="tip-title">Emite recetas digitales</div>
                                <div class="tip-desc">Genera recetas directamente desde el sistema</div>
                            </div>
                        </div>
                        <div class="tip-item">
                            <div class="tip-icon teal"><i class="fas fa-flask"></i></div>
                            <div class="tip-content">
                                <div class="tip-title">Revisa resultados de laboratorio</div>
                                <div class="tip-desc">Nuevos resultados disponibles para revisión</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CHATBOT --}}
    <div class="chatbot-btn" id="chatbotBtn" onclick="toggleChat()">
        <i class="fas fa-comment-dots"></i>
    </div>
    <div class="chatbot-box" id="chatbotBox">
        <div class="chatbot-header">
            <div class="chatbot-header-info">
                <div class="chatbot-avatar">🧬</div>
                <div>
                    <h6>Asistente E&M</h6>
                    <small>En línea</small>
                </div>
            </div>
            <button onclick="toggleChat()" class="chatbot-close">✕</button>
        </div>
        <div class="chatbot-messages" id="chatMessages">
            <div class="message bot">
                <div class="message-bubble">¡Hola! Soy tu asistente virtual. ¿En qué puedo ayudarte hoy? 😊</div>
            </div>
        </div>
        <div class="chatbot-input">
            <input type="text" id="chatInput" placeholder="Escribe tu pregunta..." onkeypress="if(event.key==='Enter') sendMessage()">
            <button onclick="sendMessage()" id="sendBtn">➤</button>
        </div>
    </div>

    <script>
        function toggleChat() {
            document.getElementById('chatbotBox').classList.toggle('active');
        }

        async function sendMessage() {
            const input = document.getElementById('chatInput');
            const messages = document.getElementById('chatMessages');
            const sendBtn = document.getElementById('sendBtn');
            const mensaje = input.value.trim();
            if (!mensaje) return;

            messages.innerHTML += `<div class="message user"><div class="message-bubble">${escapeHtml(mensaje)}</div></div>`;
            input.value = '';
            sendBtn.disabled = true;
            messages.scrollTop = messages.scrollHeight;

            messages.innerHTML += `<div class="message bot" id="typing"><div class="message-bubble">✍️ Escribiendo...</div></div>`;
            messages.scrollTop = messages.scrollHeight;

            try {
                const response = await fetch('/chatbot/responder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ mensaje })
                });
                const data = await response.json();
                document.getElementById('typing').remove();
                messages.innerHTML += `<div class="message bot"><div class="message-bubble">${escapeHtml(data.respuesta)}</div></div>`;
            } catch (error) {
                document.getElementById('typing')?.remove();
                messages.innerHTML += `<div class="message bot"><div class="message-bubble">Lo siento, ocurrió un error.</div></div>`;
            }
            sendBtn.disabled = false;
            messages.scrollTop = messages.scrollHeight;
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }
    </script>

</body>
</html>