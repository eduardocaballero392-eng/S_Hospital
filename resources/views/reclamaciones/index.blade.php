<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Libro de Reclamaciones</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Outfit', sans-serif; background: #f4f8fc; color: #0f2a47; min-height: 100vh; }

        /* NAVBAR */
        .navbar {
            background: white; border-bottom: 1px solid #e2ecf5;
            padding: 0 40px; height: 68px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
            box-shadow: 0 2px 12px rgba(15,42,71,0.07);
        }
        .navbar-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .navbar-logo-icon {
            width: 42px; height: 42px; border-radius: 50%;
            background: #1a6b5a;
            display: flex; align-items: center; justify-content: center;
        }
        .navbar-logo-main { font-family: 'Playfair Display', serif; font-size: 18px; font-weight: 700; color: #0f2a47; }
        .navbar-logo-sub { font-size: 10px; color: #0d9e75; text-transform: uppercase; letter-spacing: 2px; }
        .navbar-back { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 500; color: #6b7a8d; text-decoration: none; transition: color 0.2s; }
        .navbar-back:hover { color: #0f2a47; }

        /* HERO */
        .hero {
            background: linear-gradient(135deg, #0f2a47 0%, #1a3a5c 50%, #0d9e75 100%);
            padding: 48px 40px; text-align: center;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
            border-radius: 100px; padding: 6px 16px;
            font-size: 13px; color: rgba(255,255,255,0.85); font-weight: 500;
            margin-bottom: 16px;
        }
        .hero h1 { font-family: 'Playfair Display', serif; font-size: clamp(24px,4vw,38px); font-weight: 700; color: white; margin-bottom: 10px; }
        .hero p { font-size: 15px; color: rgba(255,255,255,0.7); max-width: 560px; margin: 0 auto; line-height: 1.7; font-weight: 300; }

        /* STEPS */
        .steps-bar {
            background: white; border-bottom: 1px solid #e2ecf5;
            padding: 16px 40px; display: flex; justify-content: center;
        }
        .steps { display: flex; align-items: center; gap: 0; max-width: 700px; width: 100%; }
        .step-item { display: flex; align-items: center; gap: 10px; flex: 1; }
        .step-num {
            width: 32px; height: 32px; border-radius: 50%;
            background: #e2ecf5; color: #6b7a8d;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; flex-shrink: 0;
            transition: all 0.3s;
        }
        .step-num.active { background: linear-gradient(135deg, #0d9e75, #2db87a); color: white; box-shadow: 0 4px 12px rgba(13,158,117,0.3); }
        .step-num.done { background: #0d9e75; color: white; }
        .step-label { font-size: 12px; font-weight: 600; color: #6b7a8d; line-height: 1.3; }
        .step-label.active { color: #0d9e75; }
        .step-connector { flex: 1; height: 2px; background: #e2ecf5; margin: 0 8px; transition: background 0.3s; }
        .step-connector.done { background: #0d9e75; }

        /* MAIN */
        .main { max-width: 860px; margin: 32px auto 60px; padding: 0 20px; }

        /* ALERT */
        .alert-success {
            background: rgba(13,158,117,0.08); border: 1px solid rgba(13,158,117,0.25);
            border-radius: 12px; padding: 16px 20px; margin-bottom: 24px;
            display: flex; align-items: center; gap: 10px;
            font-size: 14px; color: #0b8562; font-weight: 500;
        }

        /* CARD */
        .card {
            background: white; border-radius: 20px; padding: 36px 40px;
            box-shadow: 0 4px 24px rgba(15,42,71,0.07); border: 1px solid #e2ecf5;
            display: none;
        }
        .card.active { display: block; }

        .card-header { display: flex; align-items: center; gap: 16px; margin-bottom: 28px; padding-bottom: 20px; border-bottom: 1px solid #e2ecf5; }
        .card-header-icon {
            width: 50px; height: 50px; border-radius: 14px;
            background: linear-gradient(135deg, #0f2a47, #0d9e75);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: white; flex-shrink: 0;
            box-shadow: 0 6px 16px rgba(13,158,117,0.28);
        }
        .card-header h2 { font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 700; color: #0f2a47; margin-bottom: 4px; }
        .card-header p { font-size: 13px; color: #6b7a8d; font-weight: 300; }

        /* FORM */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }
        .form-group { display: flex; flex-direction: column; gap: 7px; }
        .form-group.full { grid-column: 1 / -1; }
        label { font-size: 13px; font-weight: 600; color: #1a3a5c; display: flex; align-items: center; gap: 7px; }
        label i { color: #0d9e75; font-size: 12px; }
        .form-control {
            width: 100%; padding: 12px 16px;
            border: 1.5px solid #e2ecf5; border-radius: 10px;
            font-family: 'Outfit', sans-serif; font-size: 14px; color: #0f2a47;
            background: white; outline: none; transition: all 0.2s;
        }
        .form-control::placeholder { color: #b0bec8; }
        .form-control:focus { border-color: #0d9e75; box-shadow: 0 0 0 3px rgba(13,158,117,0.1); }
        select.form-control { cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%236b7a8d' d='M6 8L0 0h12z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px; }
        textarea.form-control { min-height: 120px; resize: vertical; line-height: 1.6; }
        .char-count { font-size: 12px; color: #6b7a8d; text-align: right; }

        /* TIPO RECLAMO */
        .tipo-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 4px; }
        .tipo-card {
            border: 2px solid #e2ecf5; border-radius: 12px; padding: 16px;
            cursor: pointer; transition: all 0.25s; text-align: center; background: white;
        }
        .tipo-card:hover { border-color: #0d9e75; }
        .tipo-card.selected { border-color: #0d9e75; background: rgba(13,158,117,0.04); box-shadow: 0 0 0 3px rgba(13,158,117,0.1); }
        .tipo-card i { font-size: 22px; color: #0d9e75; display: block; margin-bottom: 6px; }
        .tipo-card span { font-size: 13px; font-weight: 600; color: #0f2a47; }

        /* NAV BUTTONS */
        .form-nav { display: flex; justify-content: space-between; align-items: center; margin-top: 28px; padding-top: 24px; border-top: 1px solid #e2ecf5; flex-wrap: wrap; gap: 12px; }
        .btn-next { padding: 13px 28px; border-radius: 10px; background: linear-gradient(135deg,#0d9e75,#2db87a); color: white; border: none; font-family: 'Outfit',sans-serif; font-size: 14px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.25s; margin-left: auto; }
        .btn-next:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(13,158,117,0.4); }
        .btn-prev { padding: 13px 28px; border-radius: 10px; background: #f4f8fc; color: #1a3a5c; border: 1.5px solid #e2ecf5; font-family: 'Outfit',sans-serif; font-size: 14px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.25s; }
        .btn-prev:hover { background: #e2ecf5; }
        .btn-submit { padding: 13px 28px; border-radius: 10px; background: linear-gradient(135deg,#0f2a47,#0d9e75); color: white; border: none; font-family: 'Outfit',sans-serif; font-size: 14px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.25s; margin-left: auto; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(15,42,71,0.3); }
        .btn-back { padding: 11px 20px; border-radius: 10px; background: transparent; color: #6b7a8d; border: 1.5px solid #e2ecf5; font-family: 'Outfit',sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.25s; text-decoration: none; }
        .btn-back:hover { color: #0f2a47; border-color: #0f2a47; }

        /* RESUMEN */
        .resumen-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
        .resumen-card { background: #f4f8fc; border: 1px solid #e2ecf5; border-radius: 14px; padding: 20px; }
        .resumen-card h4 { font-size: 11px; font-weight: 600; color: #0d9e75; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; }
        .resumen-card p { font-size: 13px; color: #0f2a47; margin-bottom: 6px; display: flex; align-items: flex-start; gap: 8px; line-height: 1.4; }
        .resumen-card p i { color: #0d9e75; font-size: 12px; margin-top: 2px; flex-shrink: 0; }

        .aviso {
            background: rgba(15,42,71,0.04); border: 1px solid rgba(15,42,71,0.1);
            border-radius: 10px; padding: 14px 18px; margin-bottom: 20px;
            font-size: 13px; color: #6b7a8d; display: flex; align-items: flex-start; gap: 10px; line-height: 1.6;
        }
        .aviso i { color: #0d9e75; flex-shrink: 0; margin-top: 2px; }

        /* MODAL */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.55); z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .modal-box { background: white; border-radius: 24px; padding: 48px 40px; text-align: center; max-width: 440px; width: 100%; box-shadow: 0 24px 64px rgba(0,0,0,0.2); animation: popIn 0.35s ease; }
        .modal-icon { width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg,#0d9e75,#2db87a); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 30px; color: white; box-shadow: 0 8px 24px rgba(13,158,117,0.35); }
        .modal-box h3 { font-family: 'Playfair Display',serif; font-size: 24px; font-weight: 700; color: #0f2a47; margin-bottom: 12px; }
        .modal-box p { font-size: 15px; color: #6b7a8d; margin-bottom: 8px; line-height: 1.6; }
        .modal-box small { font-size: 13px; color: #b0bec8; display: block; margin-bottom: 28px; }
        .modal-btn { padding: 13px 36px; background: linear-gradient(135deg,#0d9e75,#2db87a); color: white; border: none; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; font-family: 'Outfit',sans-serif; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; }
        .modal-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(13,158,117,0.4); }
        @keyframes popIn { 0%{ transform:scale(0.8); opacity:0; } 100%{ transform:scale(1); opacity:1; } }

        /* RESPONSIVE */
        @media(max-width:640px) {
            .navbar { padding: 0 16px; }
            .hero { padding: 32px 20px; }
            .steps-bar { padding: 14px 16px; }
            .step-label { display: none; }
            .card { padding: 20px 16px; border-radius: 14px; }
            .form-grid { grid-template-columns: 1fr; }
            .form-group.full { grid-column: 1; }
            .tipo-grid { grid-template-columns: 1fr; }
            .resumen-grid { grid-template-columns: 1fr; }
            .form-nav { flex-direction: column-reverse; }
            .btn-next, .btn-submit, .btn-prev, .btn-back { width: 100%; justify-content: center; margin-left: 0; }
            .main { padding: 0 12px; }
        }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar">
    <a href="{{ url('/') }}" class="navbar-logo">
        <div class="navbar-logo-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
        </div>
        <div>
            <div class="navbar-logo-main">E&M</div>
            <div class="navbar-logo-sub">Laboratorio</div>
        </div>
    </a>
    <a href="{{ url('/') }}" class="navbar-back">
        <i class="fas fa-arrow-left"></i> Volver al inicio
    </a>
</nav>

{{-- HERO --}}
<div class="hero">
    <div class="hero-badge">
        <i class="fas fa-book"></i> Libro de Reclamaciones Virtual
    </div>
    <h1>Libro de Reclamaciones</h1>
    <p>Conforme al Código de Protección y Defensa del Consumidor, ponemos a tu disposición nuestro Libro de Reclamaciones Virtual.</p>
</div>

{{-- STEPS BAR --}}
<div class="steps-bar">
    <div class="steps">
        <div class="step-item">
            <div class="step-num active" id="snum1">1</div>
            <div class="step-label active" id="slabel1">Identificación<br>del Usuario</div>
        </div>
        <div class="step-connector" id="sconn1"></div>
        <div class="step-item">
            <div class="step-num" id="snum2">2</div>
            <div class="step-label" id="slabel2">Tipo de<br>Reclamo</div>
        </div>
        <div class="step-connector" id="sconn2"></div>
        <div class="step-item">
            <div class="step-num" id="snum3">3</div>
            <div class="step-label" id="slabel3">Detalle de la<br>Reclamación</div>
        </div>
    </div>
</div>

{{-- MAIN --}}
<div class="main">

    @if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <form id="reclamaForm" method="POST" action="{{ route('reclamaciones.store') }}">
        @csrf

        {{-- PASO 1: IDENTIFICACIÓN --}}
        <div class="card active" id="paso1">
            <div class="card-header">
                <div class="card-header-icon"><i class="fas fa-user"></i></div>
                <div>
                    <h2>Identificación del Usuario</h2>
                    <p>Ingresa tus datos personales para registrar la reclamación</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Nombre *</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Tu nombre" maxlength="100">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Apellido *</label>
                    <input type="text" name="apellido" class="form-control" placeholder="Tu apellido" maxlength="100">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-id-card"></i> Tipo de Documento *</label>
                    <select name="tipo_documento" class="form-control" id="tipoDoc">
                        <option value="">Selecciona</option>
                        <option value="DNI">DNI</option>
                        <option value="Carnet de Extranjería">Carnet de Extranjería</option>
                        <option value="Pasaporte">Pasaporte</option>
                        <option value="RUC">RUC</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-hashtag"></i> Nº Documento *</label>
                    <input type="text" name="nro_documento" class="form-control" placeholder="Nº de documento" id="nroDoc" maxlength="15">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email *</label>
                    <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.com">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Teléfono</label>
                    <input type="tel" name="telefono" class="form-control" placeholder="Ej: 987654321" maxlength="9">
                </div>
                <div class="form-group full">
                    <label><i class="fas fa-map-marker-alt"></i> Dirección</label>
                    <input type="text" name="direccion" class="form-control" placeholder="Tu dirección completa">
                </div>
            </div>

            <div class="form-nav">
                <a href="{{ url('/') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Volver</a>
                <button type="button" class="btn-next" onclick="irPaso(2)">Siguiente <i class="fas fa-arrow-right"></i></button>
            </div>
        </div>

        {{-- PASO 2: TIPO DE RECLAMO --}}
        <div class="card" id="paso2">
            <div class="card-header">
                <div class="card-header-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div>
                    <h2>Tipo de Reclamo</h2>
                    <p>Selecciona la categoría que mejor describe tu situación</p>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label><i class="fas fa-tag"></i> Selecciona el tipo *</label>
                <div class="tipo-grid">
                    <div class="tipo-card" data-tipo="Reclamo" onclick="seleccionarTipo(this)">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Reclamo</span>
                    </div>
                    <div class="tipo-card" data-tipo="Queja" onclick="seleccionarTipo(this)">
                        <i class="fas fa-comment-alt"></i>
                        <span>Queja</span>
                    </div>
                    <div class="tipo-card" data-tipo="Sugerencia" onclick="seleccionarTipo(this)">
                        <i class="fas fa-lightbulb"></i>
                        <span>Sugerencia</span>
                    </div>
                    <div class="tipo-card" data-tipo="Felicitación" onclick="seleccionarTipo(this)">
                        <i class="fas fa-star"></i>
                        <span>Felicitación</span>
                    </div>
                </div>
                <input type="hidden" name="tipo_reclamo" id="tipoReclamo">
            </div>

            <div class="form-nav">
                <button type="button" class="btn-prev" onclick="irPaso(1)"><i class="fas fa-arrow-left"></i> Atrás</button>
                <button type="button" class="btn-next" onclick="irPaso(3)">Siguiente <i class="fas fa-arrow-right"></i></button>
            </div>
        </div>

        {{-- PASO 3: DETALLE --}}
        <div class="card" id="paso3">
            <div class="card-header">
                <div class="card-header-icon"><i class="fas fa-file-alt"></i></div>
                <div>
                    <h2>Detalle de la Reclamación</h2>
                    <p>Describe detalladamente tu reclamo o queja</p>
                </div>
            </div>

            {{-- RESUMEN --}}
            <div class="resumen-grid" id="resumenGrid"></div>

            <div class="form-group" style="margin-bottom: 8px;">
                <label><i class="fas fa-pencil-alt"></i> Descripción detallada *</label>
                <textarea name="detalle" id="detalle" class="form-control" placeholder="Describe detalladamente tu reclamo, queja o sugerencia. Incluye fechas, nombres y cualquier información relevante." maxlength="1000"></textarea>
            </div>
            <div class="char-count"><span id="charCount">0</span>/1000</div>

            <div class="aviso" style="margin-top: 20px;">
                <i class="fas fa-info-circle"></i>
                <span>Conforme al Artículo 5° del Reglamento del Libro de Reclamaciones, E&M Laboratorio deberá dar respuesta en un plazo no mayor a <strong>15 días hábiles</strong>.</span>
            </div>

            <div class="form-nav">
                <button type="button" class="btn-prev" onclick="irPaso(2)"><i class="fas fa-arrow-left"></i> Atrás</button>
                <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Enviar Reclamación</button>
            </div>
        </div>

    </form>
</div>

{{-- MODAL ÉXITO --}}
@if(session('success'))
<div class="modal-overlay" id="modalExito">
    <div class="modal-box">
        <div class="modal-icon"><i class="fas fa-check"></i></div>
        <h3>¡Reclamación Enviada!</h3>
        <p>{{ session('success') }}</p>
        <small>Se enviará una copia a tu correo electrónico.</small>
        <button class="modal-btn" onclick="window.location.href='/'">
            <i class="fas fa-home"></i> Ir al inicio
        </button>
    </div>
</div>
@endif

<script>
let pasoActual = 1;

function irPaso(paso) {
    // Validaciones
    if (paso === 2) {
        const nombre = document.querySelector('input[name="nombre"]')?.value?.trim();
        const apellido = document.querySelector('input[name="apellido"]')?.value?.trim();
        const tipoDoc = document.getElementById('tipoDoc')?.value;
        const nroDoc = document.getElementById('nroDoc')?.value?.trim();
        const email = document.querySelector('input[name="email"]')?.value?.trim();
        if (!nombre)   { alert('El nombre es obligatorio.'); return; }
        if (!apellido) { alert('El apellido es obligatorio.'); return; }
        if (!tipoDoc)  { alert('Selecciona el tipo de documento.'); return; }
        if (!nroDoc)   { alert('Ingresa el número de documento.'); return; }
        if (!email)    { alert('El correo es obligatorio.'); return; }
    }
    if (paso === 3) {
        const tipo = document.getElementById('tipoReclamo')?.value;
        if (!tipo) { alert('Selecciona el tipo de reclamo.'); return; }
        actualizarResumen();
    }

    // Ocultar paso actual
    document.getElementById(`paso${pasoActual}`)?.classList.remove('active');

    // Actualizar indicadores
    const numEl = document.getElementById(`snum${pasoActual}`);
    const labelEl = document.getElementById(`slabel${pasoActual}`);
    if (numEl) { numEl.classList.remove('active'); numEl.classList.add('done'); }
    if (labelEl) labelEl.classList.remove('active');
    if (pasoActual < 3) {
        const conn = document.getElementById(`sconn${pasoActual}`);
        if (conn) conn.classList.add('done');
    }

    pasoActual = paso;

    // Mostrar nuevo paso
    document.getElementById(`paso${pasoActual}`)?.classList.add('active');
    const newNum = document.getElementById(`snum${pasoActual}`);
    const newLabel = document.getElementById(`slabel${pasoActual}`);
    if (newNum) { newNum.classList.remove('done'); newNum.classList.add('active'); }
    if (newLabel) newLabel.classList.add('active');

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function seleccionarTipo(el) {
    document.querySelectorAll('.tipo-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('tipoReclamo').value = el.dataset.tipo;
}

function actualizarResumen() {
    const nombre   = document.querySelector('input[name="nombre"]')?.value || '';
    const apellido = document.querySelector('input[name="apellido"]')?.value || '';
    const tipoDoc  = document.getElementById('tipoDoc')?.value || '';
    const nroDoc   = document.getElementById('nroDoc')?.value || '';
    const email    = document.querySelector('input[name="email"]')?.value || '';
    const telefono = document.querySelector('input[name="telefono"]')?.value || 'No indicado';
    const tipo     = document.getElementById('tipoReclamo')?.value || '';

    document.getElementById('resumenGrid').innerHTML = `
        <div class="resumen-card">
            <h4><i class="fas fa-user"></i> Datos del Usuario</h4>
            <p><i class="fas fa-user"></i> <strong>${nombre} ${apellido}</strong></p>
            <p><i class="fas fa-id-card"></i> ${tipoDoc}: ${nroDoc}</p>
            <p><i class="fas fa-envelope"></i> ${email}</p>
            <p><i class="fas fa-phone"></i> ${telefono}</p>
        </div>
        <div class="resumen-card">
            <h4><i class="fas fa-tag"></i> Tipo de Reclamo</h4>
            <p><i class="fas fa-exclamation-circle"></i> <strong>${tipo}</strong></p>
            <p><i class="fas fa-calendar"></i> Fecha: ${new Date().toLocaleDateString('es-PE')}</p>
        </div>
    `;
}

// Contador caracteres
document.getElementById('detalle')?.addEventListener('input', function () {
    document.getElementById('charCount').textContent = this.value.length;
});
</script>
</body>
</html>