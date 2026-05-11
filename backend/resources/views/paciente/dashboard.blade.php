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
        <<div class="nav-right">
    <div class="user-info">
        <div class="user-name">{{ $usuario->nombre ?? 'Usuario' }}</div>
        <div class="user-role">PACIENTE</div>
    </div>
    <div class="avatar">{{ strtoupper(substr($usuario->nombre ?? 'U', 0, 1)) }}</div>
    
    {{-- BOTÓN DE SALIR / CERRAR SESIÓN --}}
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
                    <i class="fas fa-flask"></i>
                    <div class="stat-info">
                        <div class="stat-number">{{ $resultadosPendientes ?? 0 }}</div>
                        <div class="stat-label">Resultados nuevos</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECCIÓN: MIS RESULTADOS --}}
        <div class="section-label">
            Resultados recientes
        </div>
        <div class="resultados-destacados">
            @if(isset($resultados) && count($resultados) > 0)
                @foreach($resultados->take(2) as $resultado)
                <div class="resultado-card">
                    <div class="resultado-icon">
                        <i class="fas fa-flask"></i>
                    </div>
                    <div class="resultado-info">
                        <div class="resultado-nombre">{{ $resultado->nombre_examen }}</div>
                        <div class="resultado-fecha">{{ $resultado->fecha_resultado }}</div>
                        <div class="resultado-estado {{ $resultado->estado }}">
                            <i class="fas {{ $resultado->estado == 'normal' ? 'fa-check-circle' : 'fa-exclamation-triangle' }}"></i>
                            {{ ucfirst($resultado->estado) }}
                        </div>
                    </div>
                    <a href="{{ route('paciente.resultados') }}" class="resultado-ver">
                        Ver <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                @endforeach
                <a href="{{ route('paciente.resultados') }}" class="ver-todos-link">
                    Ver todos mis resultados <i class="fas fa-arrow-right"></i>
                </a>
            @else
                <div class="resultado-vacio">
                    <i class="fas fa-chart-line"></i>
                    <p>Aún no tienes resultados disponibles.</p>
                    <span>Cuando tengas exámenes listos, aparecerán aquí.</span>
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

            

            <a href="{{ route('paciente.resultados') }}" class="action-card">
                <div class="ac-icon aci-teal"><i class="fas fa-chart-line"></i></div>
                <div class="ac-text">
                    <div class="ac-title">Todos los resultados</div>
                    <div class="ac-sub">Ver historial completo de exámenes</div>
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
                    <div class="cita-card">
                        <div class="cita-fecha">
                            <div class="cita-dia">{{ \Carbon\Carbon::parse($proximaCita->fecha_hora)->format('d') }}</div>
                            <div class="cita-mes">{{ \Carbon\Carbon::parse($proximaCita->fecha_hora)->translatedFormat('M') }}</div>
                        </div>
                        <div class="cita-info">
                            <div class="cita-medico">Dr(a). {{ $proximaCita->medico_nombre }}</div>
                            <div class="cita-especialidad">{{ $proximaCita->especialidad }} · {{ \Carbon\Carbon::parse($proximaCita->fecha_hora)->format('h:i A') }}</div>
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
                            <div class="consejo-titulo"> Toma tu medicación a tiempo</div>
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
                            <div class="consejo-titulo">Controla tu presión arterial</div>
                            <div class="consejo-desc">Mídela cada 2 días y anota los valores</div>
                        </div>
                    </div>
                    <div class="consejo-item">
                        <div class="consejo-icon ci-amber"><i class="fas fa-tint"></i></div>
                        <div class="consejo-text">
                            <div class="consejo-titulo"> Hidratación: 8 vasos de agua al día</div>
                            <div class="consejo-desc">Esencial para el funcionamiento del organismo</div>
                        </div>
                    </div>
                    <div class="consejo-item">
                        <div class="consejo-icon ci-purple"><i class="fas fa-utensils"></i></div>
                        <div class="consejo-text">
                            <div class="consejo-titulo"> Alimentación balanceada</div>
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