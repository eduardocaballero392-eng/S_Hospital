<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Resultados</title>
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
            <span class="brand-name">Clínica<span>Salud</span></span>
        </div>
        <div class="nav-right">
            <div class="user-info">
                <div class="user-name">{{ $usuario->nombre }}</div>
                <div class="user-role">Paciente</div>
            </div>
            <div class="avatar">{{ strtoupper(substr($usuario->nombre, 0, 1)) }}</div>
            <a href="{{ route('paciente.dashboard') }}" class="btn-back">← Volver</a>
        </div>
    </nav>

    <div class="main">

        {{-- HEADER --}}
        <div class="page-header">
            <div class="page-header-icon" style="background:#e1f5ee;">
                <svg viewBox="0 0 24 24" fill="none" stroke="#0f6e56" stroke-width="1.8">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
            </div>
            <div>
                <h2 class="page-title-text">Mis Resultados</h2>
                <p class="page-subtitle">Resultados de tus exámenes y estudios médicos</p>
            </div>
        </div>

        {{-- FILTROS --}}
        <div class="filtros-bar">
            <div class="filtros-left">
                <button class="filtro-btn active" onclick="filtrar('todos', this)">Todos</button>
                <button class="filtro-btn" onclick="filtrar('laboratorio', this)">Laboratorio</button>
                <button class="filtro-btn" onclick="filtrar('imagen', this)">Imágenes</button>
                <button class="filtro-btn" onclick="filtrar('electrocardiograma', this)">ECG</button>
            </div>
            <div class="filtros-right">
                <input type="text" class="search-input" placeholder="🔍 Buscar resultado..." oninput="buscar(this.value)">
            </div>
        </div>

        {{-- STATS --}}
        <div class="diag-stats">
            <div class="diag-stat-card">
                <div class="diag-stat-icon" style="background:#e1f5ee;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#0f6e56" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <div>
                    <div class="diag-stat-num">4</div>
                    <div class="diag-stat-lbl">Total resultados</div>
                </div>
            </div>
            <div class="diag-stat-card">
                <div class="diag-stat-icon" style="background:#e6f1fb;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#1a5fa8" stroke-width="1.8"><path d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2v-4M9 21H5a2 2 0 01-2-2v-4m0 0h18"/></svg>
                </div>
                <div>
                    <div class="diag-stat-num">2</div>
                    <div class="diag-stat-lbl">Laboratorio</div>
                </div>
            </div>
            <div class="diag-stat-card">
                <div class="diag-stat-icon" style="background:#faeeda;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#854f0b" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </div>
                <div>
                    <div class="diag-stat-num">1</div>
                    <div class="diag-stat-lbl">Imágenes</div>
                </div>
            </div>
            <div class="diag-stat-card">
                <div class="diag-stat-icon" style="background:#eeedfe;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#534ab7" stroke-width="1.8"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
                <div>
                    <div class="diag-stat-num">1</div>
                    <div class="diag-stat-lbl">ECG</div>
                </div>
            </div>
        </div>

        {{-- LISTA RESULTADOS --}}
        <div class="resultados-lista" id="resultadosLista">

            {{-- Resultado 1 --}}
            <div class="resultado-card" data-tipo="laboratorio">
                <div class="resultado-card-left">
                    <div class="resultado-tipo-icon" style="background:#e6f1fb;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#1a5fa8" stroke-width="1.8"><path d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2v-4M9 21H5a2 2 0 01-2-2v-4m0 0h18"/></svg>
                    </div>
                    <div>
                        <div class="resultado-tipo-badge laboratorio">Laboratorio</div>
                        <div class="resultado-fecha">15 Mar 2026</div>
                    </div>
                </div>
                <div class="resultado-info">
                    <h4 class="resultado-nombre">Hemograma completo</h4>
                    <p class="resultado-desc">Análisis completo de sangre incluyendo glóbulos rojos, blancos y plaquetas.</p>
                    <div class="resultado-medico">
                        <span>👨‍⚕️ Dr. Ramírez López</span>
                        <span class="resultado-estado normal">Normal</span>
                    </div>
                </div>
                <div class="resultado-actions">
                    <button class="btn-ver-resultado" onclick="verResultado(this)">Ver resultado</button>
                    <button class="btn-descargar">⬇️ PDF</button>
                </div>
            </div>

            {{-- Resultado 2 --}}
            <div class="resultado-card" data-tipo="laboratorio">
                <div class="resultado-card-left">
                    <div class="resultado-tipo-icon" style="background:#e6f1fb;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#1a5fa8" stroke-width="1.8"><path d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2v-4M9 21H5a2 2 0 01-2-2v-4m0 0h18"/></svg>
                    </div>
                    <div>
                        <div class="resultado-tipo-badge laboratorio">Laboratorio</div>
                        <div class="resultado-fecha">02 Abr 2026</div>
                    </div>
                </div>
                <div class="resultado-info">
                    <h4 class="resultado-nombre">Glucosa en ayunas</h4>
                    <p class="resultado-desc">Medición de nivel de glucosa en sangre tras 8 horas de ayuno.</p>
                    <div class="resultado-medico">
                        <span>👨‍⚕️ Dra. Torres Vega</span>
                        <span class="resultado-estado atencion">Requiere atención</span>
                    </div>
                </div>
                <div class="resultado-actions">
                    <button class="btn-ver-resultado" onclick="verResultado(this)">Ver resultado</button>
                    <button class="btn-descargar">⬇️ PDF</button>
                </div>
            </div>

            {{-- Resultado 3 --}}
            <div class="resultado-card" data-tipo="imagen">
                <div class="resultado-card-left">
                    <div class="resultado-tipo-icon" style="background:#faeeda;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#854f0b" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    </div>
                    <div>
                        <div class="resultado-tipo-badge imagen">Imagen</div>
                        <div class="resultado-fecha">20 Abr 2026</div>
                    </div>
                </div>
                <div class="resultado-info">
                    <h4 class="resultado-nombre">Radiografía de tórax</h4>
                    <p class="resultado-desc">Estudio radiológico de tórax en proyección posteroanterior y lateral.</p>
                    <div class="resultado-medico">
                        <span>👨‍⚕️ Dr. Herrera Cruz</span>
                        <span class="resultado-estado normal">Normal</span>
                    </div>
                </div>
                <div class="resultado-actions">
                    <button class="btn-ver-resultado" onclick="verResultado(this)">Ver resultado</button>
                    <button class="btn-descargar">⬇️ PDF</button>
                </div>
            </div>

            {{-- Resultado 4 --}}
            <div class="resultado-card" data-tipo="electrocardiograma">
                <div class="resultado-card-left">
                    <div class="resultado-tipo-icon" style="background:#eeedfe;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#534ab7" stroke-width="1.8"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    </div>
                    <div>
                        <div class="resultado-tipo-badge ecg">ECG</div>
                        <div class="resultado-fecha">25 Abr 2026</div>
                    </div>
                </div>
                <div class="resultado-info">
                    <h4 class="resultado-nombre">Electrocardiograma</h4>
                    <p class="resultado-desc">Registro de la actividad eléctrica del corazón en reposo.</p>
                    <div class="resultado-medico">
                        <span>👨‍⚕️ Dr. Ramírez López</span>
                        <span class="resultado-estado normal">Normal</span>
                    </div>
                </div>
                <div class="resultado-actions">
                    <button class="btn-ver-resultado" onclick="verResultado(this)">Ver resultado</button>
                    <button class="btn-descargar">⬇️ PDF</button>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL --}}
    <div class="modal-overlay" id="modalOverlay" onclick="cerrarModal()">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h4 id="modalTitulo"></h4>
                <button onclick="cerrarModal()" class="modal-close">✕</button>
            </div>
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>

    <script>
        function filtrar(tipo, btn) {
            document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('.resultado-card').forEach(card => {
                card.style.display = (tipo === 'todos' || card.dataset.tipo === tipo) ? 'flex' : 'none';
            });
        }

        function buscar(valor) {
            document.querySelectorAll('.resultado-card').forEach(card => {
                const nombre = card.querySelector('.resultado-nombre').textContent.toLowerCase();
                card.style.display = nombre.includes(valor.toLowerCase()) ? 'flex' : 'none';
            });
        }

        function verResultado(btn) {
            const card = btn.closest('.resultado-card');
            const nombre = card.querySelector('.resultado-nombre').textContent;
            const desc = card.querySelector('.resultado-desc').textContent;
            const medico = card.querySelector('.resultado-medico span').textContent;
            const estado = card.querySelector('.resultado-estado').textContent;
            const fecha = card.querySelector('.resultado-fecha').textContent;
            const tipo = card.querySelector('.resultado-tipo-badge').textContent;

            document.getElementById('modalTitulo').textContent = nombre;
            document.getElementById('modalBody').innerHTML = `
                <div class="modal-detalle">
                    <div class="modal-row"><span class="modal-label">Tipo:</span><span class="modal-value">${tipo}</span></div>
                    <div class="modal-row"><span class="modal-label">Fecha:</span><span class="modal-value">${fecha}</span></div>
                    <div class="modal-row"><span class="modal-label">Médico:</span><span class="modal-value">${medico}</span></div>
                    <div class="modal-row"><span class="modal-label">Estado:</span><span class="modal-value">${estado}</span></div>
                    <div class="modal-row"><span class="modal-label">Descripción:</span><span class="modal-value">${desc}</span></div>
                </div>
            `;
            document.getElementById('modalOverlay').classList.add('active');
        }

        function cerrarModal() {
            document.getElementById('modalOverlay').classList.remove('active');
        }
    </script>

</body>
</html>