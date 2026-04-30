<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Diagnósticos</title>
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
        <div class="app-wrapper">

            {{-- HEADER --}}
            <div class="page-header-simple">
                <h1>Registro clínico · <span style="font-weight:400; color:#2c7a4d;">Diagnósticos</span></h1>
                <p>Historial médico estructurado con información diagnóstica, especialistas y recomendaciones.</p>
            </div>

            {{-- FILTROS --}}
            <div class="filters-section">
                <div class="filter-group">
                    <button class="filter-chip active" onclick="filtrar('todos', this)">Todos</button>
                    <button class="filter-chip" onclick="filtrar('cronico', this)">Crónico</button>
                    <button class="filter-chip" onclick="filtrar('agudo', this)">Agudo</button>
                    <button class="filter-chip" onclick="filtrar('preventivo', this)">Preventivo</button>
                </div>
                <div class="search-box">
                    <input type="text" placeholder="Buscar por patología, médico..." oninput="buscar(this.value)">
                </div>
            </div>

            {{-- STATS --}}
            <div class="stats-row">
                <div class="stat-item">
                    <div>
                        <div class="stat-number">0</div>
                        <div class="stat-label">Total diagnósticos</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div>
                        <div class="stat-number">0</div>
                        <div class="stat-label">Crónicos</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div>
                        <div class="stat-number">0</div>
                        <div class="stat-label">Agudos</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div>
                        <div class="stat-number">0</div>
                        <div class="stat-label">Preventivos</div>
                    </div>
                </div>
            </div>

            {{-- TABLA --}}
            <div class="table-diagnosticos">
                <table class="diagnostico-table" id="tablaDiagnosticos">
                    <thead>
                        <tr>
                            <th>Diagnóstico / condición</th>
                            <th>Tipo</th>
                            <th>Especialista / servicio</th>
                            <th>Fecha</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-tipo="cronico">
                            <td>
                                <div class="diagnostico-nombre">Hipertensión arterial</div>
                                <div class="diagnostico-desc">Presión arterial elevada de forma persistente. Se recomienda control mensual y reducción de sal.</div>
                            </td>
                            <td><span class="tipo-badge cronico">Crónico</span></td>
                            <td>
                                <div style="font-weight:600; font-size:0.85rem;">Dr. Ramírez López</div>
                                <div style="font-size:0.75rem; color:#6d8faa;">Cardiología</div>
                            </td>
                            <td style="font-size:0.85rem;">15 Mar 2026</td>
                            <td>
                                <button class="btn-detalle-link" onclick="verDetalle(this)">Ver detalle</button>
                            </td>
                        </tr>
                        <tr data-tipo="agudo">
                            <td>
                                <div class="diagnostico-nombre">Infección respiratoria aguda</div>
                                <div class="diagnostico-desc">Infección viral de las vías respiratorias superiores. Tratamiento con reposo y antitérmicos.</div>
                            </td>
                            <td><span class="tipo-badge agudo">Agudo</span></td>
                            <td>
                                <div style="font-weight:600; font-size:0.85rem;">Dra. Torres Vega</div>
                                <div style="font-size:0.75rem; color:#6d8faa;">Medicina General</div>
                            </td>
                            <td style="font-size:0.85rem;">02 Abr 2026</td>
                            <td>
                                <button class="btn-detalle-link" onclick="verDetalle(this)">Ver detalle</button>
                            </td>
                        </tr>
                        <tr data-tipo="preventivo">
                            <td>
                                <div class="diagnostico-nombre">Chequeo general preventivo</div>
                                <div class="diagnostico-desc">Examen médico de rutina. Resultados dentro de parámetros normales. Revisión anual recomendada.</div>
                            </td>
                            <td><span class="tipo-badge preventivo">Preventivo</span></td>
                            <td>
                                <div style="font-weight:600; font-size:0.85rem;">Dr. Herrera Cruz</div>
                                <div style="font-size:0.75rem; color:#6d8faa;">Neurología</div>
                            </td>
                            <td style="font-size:0.85rem;">20 Abr 2026</td>
                            <td>
                                <button class="btn-detalle-link" onclick="verDetalle(this)">Ver detalle</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- MODAL --}}
    <div class="modal-overlay" id="modalOverlay" onclick="cerrarModal()">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3 id="modalTitulo"></h3>
                <button onclick="cerrarModal()" class="modal-close">✕</button>
            </div>
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>

    <script>
        function filtrar(tipo, btn) {
            document.querySelectorAll('.filter-chip').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('#tablaDiagnosticos tbody tr').forEach(row => {
                row.style.display = (tipo === 'todos' || row.dataset.tipo === tipo) ? '' : 'none';
            });
        }

        function buscar(valor) {
            document.querySelectorAll('#tablaDiagnosticos tbody tr').forEach(row => {
                const nombre = row.querySelector('.diagnostico-nombre').textContent.toLowerCase();
                row.style.display = nombre.includes(valor.toLowerCase()) ? '' : 'none';
            });
        }

        function verDetalle(btn) {
            const row = btn.closest('tr');
            const nombre = row.querySelector('.diagnostico-nombre').textContent;
            const desc = row.querySelector('.diagnostico-desc').textContent;
            const medico = row.cells[2].querySelector('div').textContent;
            const fecha = row.cells[3].textContent.trim();
            const tipo = row.cells[1].textContent.trim();

            document.getElementById('modalTitulo').textContent = nombre;
            document.getElementById('modalBody').innerHTML = `
                <div class="detail-row">
                    <div class="detail-label">Tipo</div>
                    <div class="detail-value">${tipo}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Fecha</div>
                    <div class="detail-value">${fecha}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Médico</div>
                    <div class="detail-value">${medico}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Descripción</div>
                    <div class="detail-value">${desc}</div>
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