<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Paciente</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="dashboard-body">

    {{-- NAVBAR --}}
    <div class="top-navbar">
        <div class="navbar-left">
            <h4 class="page-title">Bienvenido, {{ $usuario->nombre }} </h4>
        </div>
        <div class="navbar-right">
            <div class="user-info">
                <div class="user-avatar">{{ strtoupper(substr($usuario->nombre, 0, 1)) }}</div>
                <span class="user-name">{{ $usuario->nombre }}</span>
            </div>
        </div>
    </div>

    {{-- CONTENIDO --}}
    <div class="dashboard-content">

        {{-- ACCIONES RAPIDAS --}}
        <div class="section-header">
            <h5> Acciones Rápidas</h5>
        </div>

        <div class="actions-grid">
            <a href="#" class="action-card">
                <div class="action-icon" style="background: #e8f4fd;">📅</div>
                <div class="action-info">
                    <h6>Agendar Cita</h6>
                    <p>Programa tu próxima consulta</p>
                </div>
                <span class="action-arrow">→</span>
            </a>

            <a href="#" class="action-card">
                <div class="action-icon" style="background: #e8f8f0;">🩺</div>
                <div class="action-info">
                    <h6>Mis Diagnósticos</h6>
                    <p>Ver historial de diagnósticos</p>
                </div>
                <span class="action-arrow">→</span>
            </a>

            <a href="#" class="action-card">
                <div class="action-icon" style="background: #fef9e7;">💊</div>
                <div class="action-info">
                    <h6>Mis Recetas</h6>
                    <p>Ver recetas médicas activas</p>
                </div>
                <span class="action-arrow">→</span>
            </a>

            <a href="#" class="action-card">
                <div class="action-icon" style="background: #fdf2f8;">📋</div>
                <div class="action-info">
                    <h6>Mis Resultados</h6>
                    <p>Ver resultados de exámenes</p>
                </div>
                <span class="action-arrow">→</span>
            </a>

            <a href="#" class="action-card">
                <div class="action-icon" style="background: #f0f4ff;">🏥</div>
                <div class="action-info">
                    <h6>Solicitar Servicio</h6>
                    <p>Pedir servicios hospitalarios</p>
                </div>
                <span class="action-arrow">→</span>
            </a>

            <a href="#" class="action-card">
                <div class="action-icon" style="background: #fff0f0;">👤</div>
                <div class="action-info">
                    <h6>Mi Perfil</h6>
                    <p>Actualizar mis datos personales</p>
                </div>
                <span class="action-arrow">→</span>
            </a>
        </div>

    </div>


    {{-- CHATBOT --}}
<div class="chatbot-btn" id="chatbotBtn" onclick="toggleChat()">
    💬
</div>

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
            <div class="message-bubble">
                ¡Hola! Soy tu asistente virtual del Hospital System. ¿En qué puedo ayudarte hoy? 😊
            </div>
        </div>
    </div>

    <div class="chatbot-input">
        <input
            type="text"
            id="chatInput"
            placeholder="Escribe tu pregunta..."
            onkeypress="if(event.key==='Enter') sendMessage()"
        >
        <button onclick="sendMessage()" id="sendBtn">➤</button>
    </div>
</div>

<script>
    function toggleChat() {
        const box = document.getElementById('chatbotBox');
        box.classList.toggle('active');
    }

    async function sendMessage() {
        const input = document.getElementById('chatInput');
        const messages = document.getElementById('chatMessages');
        const sendBtn = document.getElementById('sendBtn');
        const mensaje = input.value.trim();

        if (!mensaje) return;

        // Mensaje del usuario
        messages.innerHTML += `
            <div class="message user">
                <div class="message-bubble">${mensaje}</div>
            </div>
        `;
        input.value = '';
        sendBtn.disabled = true;
        messages.scrollTop = messages.scrollHeight;

        // Indicador de escritura
        messages.innerHTML += `
            <div class="message bot" id="typing">
                <div class="message-bubble">✦ Escribiendo...</div>
            </div>
        `;
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

            // Eliminar indicador de escritura
            document.getElementById('typing').remove();

            // Respuesta del bot
            messages.innerHTML += `
                <div class="message bot">
                    <div class="message-bubble">${data.respuesta}</div>
                </div>
            `;
        } catch (error) {
            document.getElementById('typing').remove();
            messages.innerHTML += `
                <div class="message bot">
                    <div class="message-bubble">Lo siento, ocurrió un error. Intenta de nuevo.</div>
                </div>
            `;
        }

        sendBtn.disabled = false;
        messages.scrollTop = messages.scrollHeight;
    }
</script>

</body>
</html>