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

    {{-- NAVBAR --}}
    <nav class="navbar">
        <div class="navbar-brand">
            <div class="brand-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8">
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </div>
            <span class="brand-name">E&M<span>Laboratorio</span></span>
        </div>
        <div class="nav-right">
            <div class="nav-notif">
                <i class="fas fa-bell"></i>
                <div class="notif-dot"></div>
            </div>
            <div class="user-info">
                <div class="user-name">{{ $usuario->nombre ?? 'Usuario' }}</div>
                <div class="user-role">PACIENTE</div>
            </div>
            <div class="avatar">{{ strtoupper(substr($usuario->nombre ?? 'U', 0, 1)) }}</div>
        </div>
    </nav>

    {{-- CONTENIDO --}}
    <div class="main">

        {{-- BANNER --}}
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>¡Hola, {{ $usuario->nombre ?? 'Usuario' }}! 👋</h1>
                <p>{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
            </div>
            <div class="welcome-badge">
                <div class="badge-num">0</div>
                <div class="badge-lbl">Citas este mes</div>
            </div>
        </div>

        {{-- ACCIONES RAPIDAS --}}
        <div class="section-label">Acciones rápidas</div>
        <div class="actions-grid">
            <a href="{{ route('paciente.diagnosticos') }}" class="action-card">
                <div class="ac-icon aci-green">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="ac-text">
                    <div class="ac-title">Mis diagnósticos</div>
                    <div class="ac-sub">Ver historial clínico</div>
                </div>
                <div class="ac-arrow"><i class="fas fa-arrow-right"></i></div>
            </a>

            <a href="{{ route('paciente.recetas') }}" class="action-card">
                <div class="ac-icon aci-amber">
                    <i class="fas fa-prescription-bottle"></i>
                </div>
                <div class="ac-text">
                    <div class="ac-title">Mis recetas</div>
                    <div class="ac-sub">Ver recetas médicas activas</div>
                </div>
                <div class="ac-arrow"><i class="fas fa-arrow-right"></i></div>
            </a>

            <a href="{{ route('paciente.resultados') }}" class="action-card">
                <div class="ac-icon aci-teal">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="ac-text">
                    <div class="ac-title">Mis resultados</div>
                    <div class="ac-sub">Ver resultados de exámenes</div>
                </div>
                <div class="ac-arrow"><i class="fas fa-arrow-right"></i></div>
            </a>
        </div>

        {{-- BOTTOM ROW --}}
        <div class="bottom-row">
            {{-- PRÓXIMAS CITAS --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">📅 Próximas citas</div>
                    <a href="{{ route('paciente.citas') }}" class="panel-link">Ver todas →</a>
                </div>
                <div class="cita-item">
                    <div class="cita-date">
                        <div class="cita-day">--</div>
                        <div class="cita-mon">---</div>
                    </div>
                    <div class="cita-sep"></div>
                    <div class="cita-info">
                        <div class="cita-doc">Sin citas programadas</div>
                        <div class="cita-esp">Agenda tu primera cita</div>
                    </div>
                </div>
            </div>

            {{-- CONSEJOS DE SALUD --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">💡 Consejos de salud</div>
                    <div class="panel-link" id="verMasConsejo">Ver más →</div>
                </div>
                <div class="consejo-slider" id="consejoSlider">
                    <div class="consejo-item active">
                        <div class="consejo-icon ci-blue">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="consejo-text">
                            <div class="consejo-titulo">Toma tu medicación a tiempo</div>
                            <div class="consejo-desc">Recordatorio diario · No olvides tomarlo con agua</div>
                        </div>
                    </div>
                    <div class="consejo-item">
                        <div class="consejo-icon ci-green">
                            <i class="fas fa-walking"></i>
                        </div>
                        <div class="consejo-text">
                            <div class="consejo-titulo">Camina 30 minutos al día</div>
                            <div class="consejo-desc">Mejora tu salud cardiovascular</div>
                        </div>
                    </div>
                    <div class="consejo-item">
                        <div class="consejo-icon ci-teal">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <div class="consejo-text">
                            <div class="consejo-titulo">Controla tu presión arterial</div>
                            <div class="consejo-desc">Mídela cada 2 días y anota los valores</div>
                        </div>
                    </div>
                    <div class="consejo-item">
                        <div class="consejo-icon ci-amber">
                            <i class="fas fa-tint"></i>
                        </div>
                        <div class="consejo-text">
                            <div class="consejo-titulo">Hidratación: 8 vasos de agua al día</div>
                            <div class="consejo-desc">Esencial para el funcionamiento del organismo</div>
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
                    if (dotsContainer && dotsContainer.children[i]) {
                        dotsContainer.children[i].classList.remove('active');
                    }
                });
                items[index].classList.add('active');
                if (dotsContainer && dotsContainer.children[index]) {
                    dotsContainer.children[index].classList.add('active');
                }
                currentIndex = index;
            }

            function siguienteConsejo() {
                let next = (currentIndex + 1) % items.length;
                mostrarConsejo(next);
            }

            function iniciarIntervalo() {
                if (interval) clearInterval(interval);
                interval = setInterval(siguienteConsejo, 4000);
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