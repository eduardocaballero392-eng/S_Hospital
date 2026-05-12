<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Panel Principal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="dash">

    {{-- ============================== NAVBAR ============================== --}}
    <nav class="navbar">
        <div class="navbar-brand">
            <div class="brand-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8">
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </div>
            <span class="brand-name">E&M<span>Laboratorio</span></span>
        </div>
        <div class="nav-right">
            <div class="user-info">
                <div class="user-name">{{ $usuario->nombre ?? 'Usuario' }}</div>
                <div class="user-role">PACIENTE</div>
            </div>
            <div class="avatar">{{ strtoupper(substr($usuario->nombre ?? 'U', 0, 1)) }}</div>
            
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn" title="Cerrar sesión">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </nav>

    {{-- ============================== MAIN CONTENT ============================== --}}
    <div class="main">

        {{-- BANNER DE BIENVENIDA MEJORADO --}}
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>¡Hola, {{ $usuario->nombre ?? 'Usuario' }}!</h1>
                <p>{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
            </div>
            <div class="welcome-stats">
                <div class="stat-card">
                    <i class="fas fa-calendar-check"></i>
                    <div class="stat-info">
                        <div class="stat-number">{{ $citasPendientes ?? 0 }}</div>
                        <div class="stat-label">Citas pendientes</div>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-notes-medical"></i>
                    <div class="stat-info">
                        <div class="stat-number">{{ $totalDiagnosticos ?? 0 }}</div>
                        <div class="stat-label">Diagnósticos</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECCIÓN: DIAGNÓSTICOS RECIENTES --}}
        <div class="section-label">
            Diagnósticos recientes
        </div>
        <div class="resultados-destacados diagnosticos-dash-block">
            @if(isset($diagnosticosRecientes) && $diagnosticosRecientes->count() > 0)
                @foreach($diagnosticosRecientes as $diag)
                <div class="resultado-card diag-dash-card">
                    <div class="resultado-icon diag-dash-icon">
                        <i class="fas fa-notes-medical"></i>
                    </div>
                    <div class="resultado-info">
                        <div class="resultado-nombre">{{ $diag->nombre }}</div>
                        @if($diag->tipo)
                            <div class="diag-tipo-pill">{{ ucfirst($diag->tipo) }}</div>
                        @endif
                        <div class="resultado-fecha diag-dash-meta">
                            <span><i class="fas fa-calendar-day"></i> Registrado: {{ $diag->fecha_diagnostico ? $diag->fecha_diagnostico->locale('es')->isoFormat('D MMM YYYY') : '—' }}</span>
                            @if($diag->cita?->fecha_hora)
                                <span class="diag-cita-line"><i class="fas fa-link"></i> Cita: {{ $diag->cita->fecha_hora->locale('es')->isoFormat('D MMM YYYY, HH:mm') }}</span>
                            @endif
                        </div>
                        <div class="diag-medico-line">
                            <i class="fas fa-user-md"></i> {{ $diag->medico ? $diag->medico->nombreParaMostrar() : 'Médico' }}
                            @php $espDiag = $diag->medico ? $diag->medico->especialidadParaMostrar() : ''; @endphp
                            @if($espDiag !== '')
                                <span class="diag-esp">· {{ $espDiag }}</span>
                            @endif
                        </div>
                        @if($diag->descripcion)
                            <div class="diag-desc-preview">{{ \Illuminate\Support\Str::limit(strip_tags($diag->descripcion), 120) }}</div>
                        @endif
                    </div>
                    <a href="{{ route('paciente.diagnosticos') }}" class="resultado-ver">
                        Ver <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                @endforeach
                <a href="{{ route('paciente.diagnosticos') }}" class="ver-todos-link">
                    Ver todos mis diagnósticos <i class="fas fa-arrow-right"></i>
                </a>
            @else
                <div class="resultado-vacio">
                    <i class="fas fa-notes-medical"></i>
                    <p>Aún no hay diagnósticos en tu cuenta.</p>
                    <span>Si tu médico ya te registró en el sistema, aquí aparecerán sus diagnósticos.</span>
                </div>
            @endif
        </div>

        {{-- ACCIONES RÁPIDAS --}}
        <div class="section-label">
            Acciones rápidas
        </div>
        <div class="actions-grid">
            <a href="{{ route('paciente.diagnosticos') }}" class="action-card">
                <div class="ac-icon aci-green"><i class="fas fa-file-alt"></i></div>
                <div class="ac-text">
                    <div class="ac-title">Mis diagnósticos</div>
                    <div class="ac-sub">Ver historial clínico</div>
                </div>
                <div class="ac-arrow"><i class="fas fa-arrow-right"></i></div>
            </a>
        </div>

        {{-- FILA INFERIOR: CITAS Y CONSEJOS --}}
        <div class="bottom-row">
            {{-- PRÓXIMAS CITAS --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title"><i class="fas fa-calendar-week"></i> Próximas citas</div>
                    <a href="{{ route('paciente.citas') }}" class="panel-link">Ver todas →</a>
                </div>
                @if(isset($proximaCita))
                    @php
                        $fh = \Carbon\Carbon::parse($proximaCita->fecha_hora)->timezone(config('app.timezone'))->locale('es');
                        $nomMed = preg_replace('/^(Dr\.?\s*|Dra\.?\s*|doctora?\s+)/iu', '', trim((string) ($proximaCita->medico_nombre ?? '')));
                    @endphp
                    <div class="cita-card">
                        <div class="cita-fecha">
                            <div class="cita-dia">{{ $fh->format('d') }}</div>
                            <div class="cita-mes">{{ strtoupper($fh->translatedFormat('M')) }}</div>
                        </div>
                        <div class="cita-info">
                            <div class="cita-medico">Dr(a). {{ $nomMed !== '' ? $nomMed : 'Por asignar' }}</div>
                            <div class="cita-especialidad">{{ $proximaCita->especialidad }} · {{ $fh->format('h:i A') }}</div>
                        </div>
                        <a class="cita-accion">
                            <i class="fas fa-calendar-check"></i>
                        </a>
                    </div>
                @else
                    <div class="empty-cita">
                        <i class="fas fa-calendar-day"></i>
                        <p>Sin citas próximas</p>
                        <span>El personal te contactará para nuevas citas</span>
                    </div>
                @endif
            </div>

            {{-- CONSEJOS DE SALUD --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title"><i class="fas fa-lightbulb"></i> Consejos de salud</div>
                    <div class="panel-link" id="verMasConsejo">Ver más →</div>
                </div>
                <div class="consejo-slider" id="consejoSlider">
                    <div class="consejo-item active">
                        <div class="consejo-icon ci-blue"><i class="fas fa-clock"></i></div>
                        <div class="consejo-text">
                            <div class="consejo-titulo">💊 Toma tu medicación a tiempo</div>
                            <div class="consejo-desc">Recordatorio diario · No olvides tomarlo con agua</div>
                        </div>
                    </div>
                    <div class="consejo-item">
                        <div class="consejo-icon ci-green"><i class="fas fa-walking"></i></div>
                        <div class="consejo-text">
                            <div class="consejo-titulo">🚶‍♂️ Camina 30 minutos al día</div>
                            <div class="consejo-desc">Mejora tu salud cardiovascular</div>
                        </div>
                    </div>
                    <div class="consejo-item">
                        <div class="consejo-icon ci-teal"><i class="fas fa-heartbeat"></i></div>
                        <div class="consejo-text">
                            <div class="consejo-titulo">❤️ Controla tu presión arterial</div>
                            <div class="consejo-desc">Mídela cada 2 días y anota los valores</div>
                        </div>
                    </div>
                    <div class="consejo-item">
                        <div class="consejo-icon ci-amber"><i class="fas fa-tint"></i></div>
                        <div class="consejo-text">
                            <div class="consejo-titulo">💧 Hidratación: 8 vasos de agua al día</div>
                            <div class="consejo-desc">Esencial para el funcionamiento del organismo</div>
                        </div>
                    </div>
                    <div class="consejo-item">
                        <div class="consejo-icon ci-purple"><i class="fas fa-utensils"></i></div>
                        <div class="consejo-text">
                            <div class="consejo-titulo">🍎 Alimentación balanceada</div>
                            <div class="consejo-desc">Incluye frutas, verduras y proteínas</div>
                        </div>
                    </div>
                </div>
                <div class="consejo-dots" id="consejoDots"></div>
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
                <div><h6>Asistente E&M</h6><small>En línea</small></div>
            </div>
            <button onclick="toggleChat()" class="chatbot-close">✕</button>
        </div>
        <div class="chatbot-messages" id="chatMessages">
            <div class="message bot"><div class="message-bubble">¡Hola! Soy tu asistente virtual. ¿En qué puedo ayudarte hoy? 😊</div></div>
        </div>
        <div class="chatbot-input">
            <input type="text" id="chatInput" placeholder="Escribe tu pregunta..." onkeypress="if(event.key==='Enter') sendMessage()">
            <button onclick="sendMessage()" id="sendBtn">➤</button>
        </div>
    </div>

    <style>
        .diagnosticos-dash-block {
            display: grid;
            gap: 1rem;
        }
        @media (min-width: 900px) {
            .diagnosticos-dash-block {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        .diag-dash-icon {
            background: linear-gradient(135deg, #0d9e75, #1a5fa8) !important;
        }
        .diag-tipo-pill {
            display: inline-block;
            margin-top: 0.35rem;
            padding: 0.15rem 0.55rem;
            border-radius: 999px;
            font-size: 0.68rem;
            font-weight: 600;
            text-transform: capitalize;
            background: #ecfdf5;
            color: #047857;
        }
        .diag-dash-meta {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            margin-top: 0.4rem;
            font-size: 0.78rem;
            color: #475569;
        }
        .diag-dash-meta i { margin-right: 0.35rem; color: #1a5fa8; }
        .diag-cita-line { color: #0d9488; font-weight: 500; }
        .diag-medico-line {
            margin-top: 0.45rem;
            font-size: 0.8rem;
            color: #334155;
        }
        .diag-medico-line i { color: #1a5fa8; margin-right: 0.35rem; }
        .diag-esp { color: #64748b; font-weight: 400; }
        .diag-desc-preview {
            margin-top: 0.5rem;
            font-size: 0.78rem;
            line-height: 1.35;
            color: #64748b;
        }
    </style>

    <script>
        // Carrusel de consejos
        (function() {
            const items = document.querySelectorAll('#consejoSlider .consejo-item');
            const dotsContainer = document.getElementById('consejoDots');
            let currentIndex = 0;
            let interval;

            if (dotsContainer && items.length > 0) {
                items.forEach((_, idx) => {
                    const dot = document.createElement('span');
                    dot.classList.add('consejo-dot');
                    if (idx === 0) dot.classList.add('active');
                    dot.addEventListener('click', () => {
                        clearInterval(interval);
                        mostrarConsejo(idx);
                        iniciarIntervalo();
                    });
                    dotsContainer.appendChild(dot);
                });
            }

            function mostrarConsejo(index) {
                if (index === currentIndex) return;
                items.forEach((item, i) => {
                    item.classList.remove('active');
                    if (dotsContainer?.children[i]) dotsContainer.children[i].classList.remove('active');
                });
                items[index].classList.add('active');
                if (dotsContainer?.children[index]) dotsContainer.children[index].classList.add('active');
                currentIndex = index;
            }

            function siguienteConsejo() {
                let next = (currentIndex + 1) % items.length;
                mostrarConsejo(next);
            }

            function iniciarIntervalo() {
                if (interval) clearInterval(interval);
                interval = setInterval(siguienteConsejo, 5000);
            }

            const verMasBtn = document.getElementById('verMasConsejo');
            if (verMasBtn) {
                verMasBtn.addEventListener('click', () => {
                    clearInterval(interval);
                    siguienteConsejo();
                    iniciarIntervalo();
                });
            }
            if (items.length > 0) iniciarIntervalo();
        })();

        // Chatbot
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
                document.getElementById('typing')?.remove();
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