<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>B&L Laboratorio | Agenda tu Cita</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/agenda_cita.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="citas-container">

    {{-- NAVBAR --}}
    <nav class="navbar-citas">
        <div class="nav-container">
            <div class="logo">
                <div class="logo-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </div>
                <div class="logo-text">
                    <span class="logo-main">B&L</span>
                    <span class="logo-sub">Laboratorio</span>
                </div>
            </div>
            <button class="hamburger" id="hamburger"><i class="fas fa-bars"></i></button>
            <div class="nav-links" id="navLinks">
                <a href="{{ url('/') }}"><i class="fas fa-home"></i> Inicio</a>
                @auth
                    <a href="{{ route('paciente.dashboard') }}"><i class="fas fa-th-large"></i> Dashboard</a>
                    <a href="{{ route('paciente.resultados') }}"><i class="fas fa-file-medical"></i> Resultados</a>
                    <form method="POST" action="{{ route('logout') }}" class="nav-logout">
                        @csrf
                        <button type="submit"><i class="fas fa-sign-out-alt"></i> Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Iniciar sesión</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- PROGRESS STEPS --}}
    <div class="progress-wrapper">
        <div class="progress-steps">
            <div class="step active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-label">Datos Personales</div>
            </div>
            <div class="step-line"></div>
            <div class="step" data-step="2">
                <div class="step-number">2</div>
                <div class="step-label">Motivo y Tipo</div>
            </div>
            <div class="step-line"></div>
            <div class="step" data-step="3">
                <div class="step-number">3</div>
                <div class="step-label">Fecha y Hora</div>
            </div>
            <div class="step-line"></div>
            <div class="step" data-step="4">
                <div class="step-number">4</div>
                <div class="step-label">Confirmación</div>
            </div>
        </div>
    </div>

    {{-- FORMULARIO --}}
    <div class="form-wrapper">

        @if($errors->any())
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form id="citaForm" method="POST" action="{{ route('paciente.citas.store') }}">
            @csrf

            {{-- ===== PASO 1: DATOS PERSONALES ===== --}}
            <div class="form-step active" id="step1">
                <div class="step-header">
                    <div class="step-icon"><i class="fas fa-user-circle"></i></div>
                    <div>
                        <h2>Datos Personales</h2>
                        <p>Ingresa tus datos para la cita</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label><i class="fas fa-id-card"></i> DNI *</label>
                        <input type="text" name="dni"
                               value="{{ auth()->check() ? (auth()->user()->paciente->DNI ?? '') : '' }}"
                               class="form-control" placeholder="Ej: 12345678" maxlength="8"
                               {{ auth()->check() && isset(auth()->user()->paciente->DNI) ? 'readonly' : '' }}>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Nombres *</label>
                        <input type="text" name="nombres"
                               value="{{ auth()->check() ? (auth()->user()->paciente->nombre ?? '') : '' }}"
                               class="form-control" placeholder="Tus nombres"
                               {{ auth()->check() && isset(auth()->user()->paciente->nombre) ? 'readonly' : '' }}>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Apellidos *</label>
                        <input type="text" name="apellidos"
                               value="{{ auth()->check() ? (auth()->user()->paciente->apellido ?? '') : '' }}"
                               class="form-control" placeholder="Tus apellidos"
                               {{ auth()->check() && isset(auth()->user()->paciente->apellido) ? 'readonly' : '' }}>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Fecha de Nacimiento *</label>
                        <input type="date" name="fecha_nac"
                               value="{{ auth()->check() ? (auth()->user()->paciente->fecha_nac ?? '') : '' }}"
                               class="form-control"
                               {{ auth()->check() && isset(auth()->user()->paciente->fecha_nac) ? 'readonly' : '' }}>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-venus-mars"></i> Género *</label>
                        @if(auth()->check() && isset(auth()->user()->paciente->genero))
                            <input type="text" name="genero"
                                   value="{{ auth()->user()->paciente->genero }}"
                                   class="form-control" readonly>
                        @else
                            <select name="genero" class="form-control">
                                <option value="">Selecciona tu género</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        @endif
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-phone-alt"></i> Teléfono *</label>
                        <input type="tel" name="telefono" id="telefono"
                               value="{{ auth()->check() ? (auth()->user()->paciente->telefono ?? '') : '' }}"
                               class="form-control" placeholder="Ej: 987654321" maxlength="9">
                    </div>

                    <div class="form-group full-width">
                        <label><i class="fas fa-map-marker-alt"></i> Dirección</label>
                        <input type="text" name="direccion" id="direccion"
                               class="form-control" placeholder="Ingresa tu dirección completa">
                    </div>

                    <div class="form-group full-width">
                        <label><i class="fas fa-envelope"></i> Correo Electrónico</label>
                        <input type="email" name="email" id="email"
                               value="{{ auth()->check() ? (auth()->user()->email ?? '') : '' }}"
                               class="form-control" placeholder="correo@ejemplo.com"
                               {{ auth()->check() && auth()->user()->email ? 'readonly' : '' }}>
                    </div>
                </div>

                <div class="form-navigation">
                    <a href="{{ url('/') }}" class="btn-back-home">
                        <i class="fas fa-arrow-left"></i> Volver al inicio
                    </a>
                    <button type="button" class="btn-next" onclick="nextStep()">
                        Siguiente <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- ===== PASO 2: MOTIVO Y TIPO ===== --}}
            <div class="form-step" id="step2">
                <div class="step-header">
                    <div class="step-icon"><i class="fas fa-clipboard-list"></i></div>
                    <div>
                        <h2>Motivo y Tipo de Cita</h2>
                        <p>Cuéntanos el motivo de tu visita</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group full-width">
                        <label><i class="fas fa-stethoscope"></i> Tipo de Cita *</label>
                        <div class="tipo-grid">
                            <div class="tipo-card" data-tipo="Laboratorio Clínico">
                                <div class="tipo-icon"><i class="fas fa-tint"></i></div>
                                <span>Laboratorio Clínico</span>
                            </div>
                            <div class="tipo-card" data-tipo="BYL Genetics">
                                <div class="tipo-icon"><i class="fas fa-dna"></i></div>
                                <span>BYL Genetics</span>
                            </div>
                            <div class="tipo-card" data-tipo="Laboratorio de Referencia">
                                <div class="tipo-icon"><i class="fas fa-microscope"></i></div>
                                <span>Laboratorio de Referencia</span>
                            </div>
                            <div class="tipo-card" data-tipo="Atención a Domicilio">
                                <div class="tipo-icon"><i class="fas fa-home"></i></div>
                                <span>Atención a Domicilio</span>
                            </div>
                        </div>
                        <input type="hidden" name="tipo" id="tipoSeleccionado">
                    </div>

                    <div class="form-group full-width">
                        <label><i class="fas fa-comment-medical"></i> Motivo de la Cita *</label>
                        <textarea name="motivo" id="motivo" class="form-control textarea-motivo"
                                  placeholder="Describe brevemente el motivo de tu visita. Ej: Análisis de sangre de rutina, control médico, etc."
                                  maxlength="255"></textarea>
                        <span class="char-count"><span id="charCount">0</span>/255</span>
                    </div>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn-prev" onclick="prevStep()">
                        <i class="fas fa-arrow-left"></i> Atrás
                    </button>
                    <button type="button" class="btn-next" onclick="nextStep()">
                        Siguiente <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- ===== PASO 3: FECHA Y HORA ===== --}}
            <div class="form-step" id="step3">
                <div class="step-header">
                    <div class="step-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div>
                        <h2>Fecha y Hora</h2>
                        <p>Elige el día y horario de tu preferencia</p>
                    </div>
                </div>

                <div class="calendario-wrapper">
                    <div class="calendario-section">
                        <h3><i class="fas fa-calendar"></i> Selecciona una fecha</h3>
                        <input type="date" id="fechaCita" class="form-control" min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="horarios-section">
                        <h3><i class="fas fa-clock"></i> Selecciona un horario</h3>
                        <div class="horarios-grid" id="horariosGrid"></div>
                    </div>
                </div>

                <input type="hidden" name="fecha_hora" id="fechaHoraCombinada">

                <div class="form-navigation">
                    <button type="button" class="btn-prev" onclick="prevStep()">
                        <i class="fas fa-arrow-left"></i> Atrás
                    </button>
                    <button type="button" class="btn-next" onclick="nextStep()">
                        Siguiente <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- ===== PASO 4: CONFIRMACIÓN ===== --}}
            <div class="form-step" id="step4">
                <div class="step-header">
                    <div class="step-icon success"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <h2>Confirmar Cita</h2>
                        <p>Revisa que todos los datos sean correctos antes de confirmar</p>
                    </div>
                </div>

                <div class="resumen-cita">
                    <div class="resumen-card">
                        <h3><i class="fas fa-user-md"></i> Datos del Paciente</h3>
                        <div class="resumen-content" id="resumenPaciente"></div>
                    </div>
                    <div class="resumen-card">
                        <h3><i class="fas fa-clipboard-list"></i> Motivo y Tipo</h3>
                        <div class="resumen-content" id="resumenMotivo"></div>
                    </div>
                    <div class="resumen-card">
                        <h3><i class="fas fa-calendar-check"></i> Fecha y Hora</h3>
                        <div class="resumen-content" id="resumenFecha"></div>
                    </div>
                </div>

                <div class="aviso-admin">
                    <i class="fas fa-info-circle"></i>
                    El médico y sala serán asignados por el administrador una vez confirmada tu cita.
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn-prev" onclick="prevStep()">
                        <i class="fas fa-arrow-left"></i> Atrás
                    </button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-check"></i> Confirmar Cita
                    </button>
                </div>
            </div>

        </form>
    </div>

    {{-- MODAL CONFIRMACIÓN --}}
    @if(session('success'))
    <div class="modal-confirmacion" id="modalConfirmacion">
        <div class="modal-box">
            
            <h3>¡Cita Confirmada!</h3>
            <p>{{ session('success') }}</p>
            <button onclick="window.location.href='/'">
                <i class="fas fa-home"></i> Ir al inicio
            </button>
        </div>
    </div>
    @endif

</div>

<script>
let currentStep = 1;
const totalSteps = 4;
let horaSeleccionada = '';
let fechaSeleccionada = '';

// Hamburger
document.getElementById('hamburger')?.addEventListener('click', () => {
    document.getElementById('navLinks')?.classList.toggle('open');
});

// Tipo de cita
document.querySelectorAll('.tipo-card').forEach(card => {
    card.addEventListener('click', function () {
        document.querySelectorAll('.tipo-card').forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
        document.getElementById('tipoSeleccionado').value = this.dataset.tipo;
    });
});

// Contador caracteres
document.getElementById('motivo')?.addEventListener('input', function () {
    document.getElementById('charCount').textContent = this.value.length;
});

// Generar horarios
function generarHorarios() {
    const horarios = ['07:00','08:00','09:00','10:00','11:00','12:00','14:00','15:00','16:00','17:00','18:00'];
    const grid = document.getElementById('horariosGrid');
    if (!grid) return;
    grid.innerHTML = '';
    horarios.forEach(hora => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'horario-btn';
        btn.innerText = hora;
        btn.addEventListener('click', function () {
            document.querySelectorAll('.horario-btn').forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
            horaSeleccionada = hora;
            actualizarFechaHora();
        });
        grid.appendChild(btn);
    });
}

function actualizarFechaHora() {
    if (fechaSeleccionada && horaSeleccionada) {
        document.getElementById('fechaHoraCombinada').value = fechaSeleccionada + ' ' + horaSeleccionada + ':00';
    }
}

document.getElementById('fechaCita')?.addEventListener('change', function () {
    fechaSeleccionada = this.value;
    horaSeleccionada = '';
    document.querySelectorAll('.horario-btn').forEach(b => b.classList.remove('selected'));
    document.getElementById('fechaHoraCombinada').value = '';
});

// Navegación
function showStep(step) {
    for (let i = 1; i <= totalSteps; i++) {
        document.getElementById(`step${i}`)?.classList.toggle('active', i === step);
        const ind = document.querySelector(`.step[data-step="${i}"]`);
        if (ind) {
            ind.classList.toggle('active', i === step);
            ind.classList.toggle('done', i < step);
        }
        const lines = document.querySelectorAll('.step-line');
        if (lines[i - 1]) lines[i - 1].classList.toggle('done', i < step);
    }
    currentStep = step;
    window.scrollTo({ top: 0, behavior: 'smooth' });
    if (step === 4) actualizarResumen();
}

function nextStep() {
    if (currentStep === 1) {
        const dni     = document.querySelector('input[name="dni"]')?.value?.trim();
        const nombres = document.querySelector('input[name="nombres"]')?.value?.trim();
        const apells  = document.querySelector('input[name="apellidos"]')?.value?.trim();
        const tel     = document.getElementById('telefono')?.value?.trim();
        const genero  = document.querySelector('select[name="genero"]')?.value ||
                        document.querySelector('input[name="genero"]')?.value;
        if (!dni || dni.length < 8) { alert('Ingresa un DNI válido de 8 dígitos.'); return; }
        if (!nombres)               { alert('Los nombres son obligatorios.'); return; }
        if (!apells)                { alert('Los apellidos son obligatorios.'); return; }
        if (!genero)                { alert('Selecciona tu género.'); return; }
        if (!tel || tel.length < 9) { alert('Ingresa un teléfono válido de 9 dígitos.'); return; }
    }
    if (currentStep === 2) {
        const tipo   = document.getElementById('tipoSeleccionado')?.value;
        const motivo = document.getElementById('motivo')?.value?.trim();
        if (!tipo)   { alert('Por favor selecciona el tipo de cita.'); return; }
        if (!motivo) { alert('Por favor describe el motivo de tu cita.'); return; }
    }
    if (currentStep === 3) {
        const fh = document.getElementById('fechaHoraCombinada')?.value;
        if (!fh) { alert('Por favor selecciona una fecha y un horario.'); return; }
    }
    if (currentStep < totalSteps) showStep(currentStep + 1);
}

function prevStep() {
    if (currentStep > 1) showStep(currentStep - 1);
}

function actualizarResumen() {
    const nombre   = document.querySelector('input[name="nombres"]')?.value || '';
    const apellido = document.querySelector('input[name="apellidos"]')?.value || '';
    const dni      = document.querySelector('input[name="dni"]')?.value || '';
    const tel      = document.getElementById('telefono')?.value || '';
    const email    = document.getElementById('email')?.value || 'No especificado';
    const dir      = document.getElementById('direccion')?.value || 'No especificada';

    document.getElementById('resumenPaciente').innerHTML = `
        <p><i class="fas fa-user"></i> <strong>${nombre} ${apellido}</strong></p>
        <p><i class="fas fa-id-card"></i> DNI: ${dni}</p>
        <p><i class="fas fa-phone"></i> ${tel}</p>
        <p><i class="fas fa-envelope"></i> ${email}</p>
        <p><i class="fas fa-map-marker-alt"></i> ${dir}</p>
    `;

    const tipo   = document.getElementById('tipoSeleccionado')?.value || '';
    const motivo = document.getElementById('motivo')?.value || '';
    document.getElementById('resumenMotivo').innerHTML = `
        <p><i class="fas fa-stethoscope"></i> <strong>${tipo}</strong></p>
        <p><i class="fas fa-comment-medical"></i> ${motivo}</p>
    `;

    const fh = document.getElementById('fechaHoraCombinada')?.value || '';
    const partes = fh.split(' ');
    document.getElementById('resumenFecha').innerHTML = `
        <p><i class="fas fa-calendar"></i> <strong>${partes[0] || 'No seleccionada'}</strong></p>
        <p><i class="fas fa-clock"></i> <strong>${partes[1] ? partes[1].substring(0,5) : 'No seleccionada'}</strong></p>
        <p><i class="fas fa-info-circle"></i> Estado: <span class="badge-pendiente">Pendiente</span></p>
    `;
}

generarHorarios();
</script>
</body>
</html>