<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="dash">

    {{-- NAVBAR --}}
    <nav class="navbar">
        <div class="navbar-brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#fff" stroke-width="2">
                    <rect x="11" y="2" width="2" height="20" rx="1"/>
                    <rect x="2" y="11" width="20" height="2" rx="1"/>
                </svg>
            </div>
            <span class="brand-name">Hospital<span>Salud</span></span>
        </div>
        <div class="nav-right">
            <div class="nav-notif">
                <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
                <div class="notif-dot"></div>
            </div>
            <div class="user-info">
                <div class="user-name">{{ $usuario->nombre }}</div>
                <div class="user-role">Paciente</div>
            </div>
            <div class="avatar">{{ strtoupper(substr($usuario->nombre, 0, 1)) }}</div>
        </div>
    </nav>

    {{-- CONTENIDO --}}
    <div class="main">

        {{-- BANNER --}}
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>Bienvenido, {{ $usuario->nombre }}</h1>
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
            
            <a href="{{ route('paciente.citas') }}" class="action-card">
                <div class="ac-icon aci-blue">
                    <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01"/></svg>
                </div>
                <div class="ac-text"><div class="ac-title">Agendar cita</div><div class="ac-sub">Programa tu próxima consulta</div></div>
                <div class="ac-arrow"><svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
            </a>
            <a href="#" class="action-card">
                <div class="ac-icon aci-green">
                    <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
                <div class="ac-text"><div class="ac-title">Mis diagnósticos</div><div class="ac-sub">Ver historial clínico</div></div>
                <div class="ac-arrow"><svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
            </a>
            <a href="#" class="action-card">
                <div class="ac-icon aci-amber">
                    <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 5h6M12 12v4M10 14h4"/></svg>
                </div>
                <div class="ac-text"><div class="ac-title">Mis recetas</div><div class="ac-sub">Ver recetas médicas activas</div></div>
                <div class="ac-arrow"><svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
            </a>
            <a href="#" class="action-card">
                <div class="ac-icon aci-teal">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
                <div class="ac-text"><div class="ac-title">Mis resultados</div><div class="ac-sub">Ver resultados de exámenes</div></div>
                <div class="ac-arrow"><svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
            </a>
            <a href="#" class="action-card">
                <div class="ac-icon aci-coral">
                    <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <div class="ac-text"><div class="ac-title">Solicitar servicio</div><div class="ac-sub">Pedir servicios hospitalarios</div></div>
                <div class="ac-arrow"><svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
            </a>
            <a href="#" class="action-card">
                <div class="ac-icon aci-purple">
                    <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div class="ac-text"><div class="ac-title">Mi perfil</div><div class="ac-sub">Actualizar datos personales</div></div>
                <div class="ac-arrow"><svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
            </a>
        </div>

        {{-- BOTTOM ROW --}}
        <div class="bottom-row">

            {{-- PROXIMAS CITAS --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">Próximas citas</div>
                    <div class="panel-link">Ver todas</div>
                </div>
                <div class="cita-item">
                    <div class="cita-date"><div class="cita-day">--</div><div class="cita-mon">---</div></div>
                    <div class="cita-sep"></div>
                    <div class="cita-info">
                        <div class="cita-doc">Sin citas programadas</div>
                        <div class="cita-esp">Agenda tu primera cita</div>
                    </div>
                </div>
            </div>

            {{-- CONSEJOS --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">Consejos de salud</div>
                    <div class="panel-link">Ver más</div>
                </div>
                <div class="consejo-item">
                    <div class="consejo-icon ci-blue">
                        <svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 100 20A10 10 0 0012 2z"/><path d="M12 6v6l4 2"/></svg>
                    </div>
                    <div class="consejo-text">
                        <div class="consejo-titulo">Toma tu medicación a tiempo</div>
                        <div class="consejo-desc">Recordatorio diario · No olvides tomarlo con agua</div>
                    </div>
                </div>
                <div class="consejo-item">
                    <div class="consejo-icon ci-green">
                        <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div class="consejo-text">
                        <div class="consejo-titulo">Camina 30 minutos al día</div>
                        <div class="consejo-desc">Mejora tu salud cardiovascular</div>
                    </div>
                </div>
                <div class="consejo-item">
                    <div class="consejo-icon ci-teal">
                        <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </div>
                    <div class="consejo-text">
                        <div class="consejo-titulo">Controla tu presión arterial</div>
                        <div class="consejo-desc">Mídela cada 2 días y anota los valores</div>
                    </div>
                </div>
                <div class="consejo-item">
                    <div class="consejo-icon ci-amber">
                        <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/></svg>
                    </div>
                    <div class="consejo-text">
                        <div class="consejo-titulo">Hidratación: 8 vasos de agua al día</div>
                        <div class="consejo-desc">Esencial para el funcionamiento del organismo</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- CHATBOT --}}
    <div class="chatbot-btn" id="chatbotBtn" onclick="toggleChat()">💬</div>
    <div class="chatbot-box" id="chatbotBox">
        <div class="chatbot-header">
            <div class="chatbot-header-info">
                <div class="chatbot-avatar">🏥</div>
                <div>
                    <h6>Asistente Hospital</h6>
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

            messages.innerHTML += `<div class="message user"><div class="message-bubble">${mensaje}</div></div>`;
            input.value = '';
            sendBtn.disabled = true;
            messages.scrollTop = messages.scrollHeight;

            messages.innerHTML += `<div class="message bot" id="typing"><div class="message-bubble">✦ Escribiendo...</div></div>`;
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
                messages.innerHTML += `<div class="message bot"><div class="message-bubble">${data.respuesta}</div></div>`;
            } catch (error) {
                document.getElementById('typing').remove();
                messages.innerHTML += `<div class="message bot"><div class="message-bubble">Lo siento, ocurrió un error.</div></div>`;
            }

            sendBtn.disabled = false;
            messages.scrollTop = messages.scrollHeight;
        }
    </script>

</body>
</html>