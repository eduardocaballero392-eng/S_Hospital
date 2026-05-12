<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Mis Diagnósticos</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/diagnostico.css') }}">
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
            <div class="user-info">
                <div class="user-name">{{ $usuario->nombre ?? 'Usuario' }}</div>
                <div class="user-role">PACIENTE</div>
            </div>
            <div class="avatar">{{ strtoupper(substr($usuario->nombre ?? 'U', 0, 1)) }}</div>
            <a href="{{ route('paciente.dashboard') }}" class="btn-back">← Volver</a>
        </div>
    </nav>

    <div class="main">
        <div class="content-wrapper">

            {{-- HEADER --}}
            <div class="page-header-simple">
                <h1>Registro clínico · <span>Diagnósticos</span></h1>
                <p>Historial médico estructurado con información diagnóstica, especialistas y recomendaciones.</p>
            </div>

            {{-- FILTROS CON SELECT --}}
            <div class="filters-bar">
                <div class="filtros-left">
                    <div class="select-group">
                        <label><i class="fas fa-filter"></i> Tipo:</label>
                        <select id="filtroTipo" class="filtro-select">
                            <option value="todos">Todos los diagnósticos</option>
                            <option value="cronico">Crónico</option>
                            <option value="agudo">Agudo</option>
                            <option value="preventivo">Preventivo</option>
                        </select>
                    </div>
                </div>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchDiagnostico" placeholder="Buscar por patología, médico...">
                    <button id="limpiarFiltros" class="btn-limpiar">
                        <i class="fas fa-eraser"></i> Limpiar
                    </button>
                </div>
            </div>

            {{-- ESTADÍSTICAS --}}
            <div class="stats-row">
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                    <div>
                        <div class="stat-number" id="totalCount">0</div>
                        <div class="stat-label">Total diagnósticos</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="stat-number" id="cronicoCount">0</div>
                        <div class="stat-label">Crónicos</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-bolt"></i></div>
                    <div>
                        <div class="stat-number" id="agudoCount">0</div>
                        <div class="stat-label">Agudos</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-shield-alt"></i></div>
                    <div>
                        <div class="stat-number" id="preventivoCount">0</div>
                        <div class="stat-label">Preventivos</div>
                    </div>
                </div>
            </div>

            {{-- TABLA DE DIAGNÓSTICOS --}}
            <div class="table-container">
                <table class="diagnostico-table" id="tablaDiagnosticos">
                    <thead>
                        <tr>
                            <th>Diagnóstico / condición</th>
                            <th>Tipo</th>
                            <th>Especialista / servicio</th>
                            <th>Fecha diagnóstico</th>
                            <th>Cita agendada</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Datos dinámicos -->
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
        const diagnosticosData = @json($diagnosticosForJs ?? []);

        let activeFilter = 'todos';

        function renderTable() {
            const searchTerm = document.getElementById('searchDiagnostico')?.value.toLowerCase() || '';
            let filtered = [...diagnosticosData];

            if (activeFilter !== 'todos') {
                filtered = filtered.filter(d => d.tipo === activeFilter);
            }

            if (searchTerm !== '') {
                filtered = filtered.filter(d =>
                    (d.nombre || '').toLowerCase().includes(searchTerm) ||
                    (d.especialista || '').toLowerCase().includes(searchTerm) ||
                    (d.servicio || '').toLowerCase().includes(searchTerm) ||
                    (d.fecha_cita || '').toLowerCase().includes(searchTerm)
                );
            }

            // Actualizar estadísticas
            document.getElementById('totalCount').innerText = diagnosticosData.length;
            document.getElementById('cronicoCount').innerText = diagnosticosData.filter(d => d.tipo === 'cronico').length;
            document.getElementById('agudoCount').innerText = diagnosticosData.filter(d => d.tipo === 'agudo').length;
            document.getElementById('preventivoCount').innerText = diagnosticosData.filter(d => d.tipo === 'preventivo').length;

            const tbody = document.getElementById('tableBody');
            if (filtered.length === 0) {
                tbody.innerHTML = `<tr class="empty-row"><td colspan="6">No se encontraron diagnósticos con los filtros seleccionados.</td></tr>`;
                return;
            }

            let html = '';
            filtered.forEach(d => {
                let badgeClass = '';
                const tipo = d.tipo || 'preventivo';
                if (tipo === 'cronico') badgeClass = 'cronico';
                else if (tipo === 'agudo') badgeClass = 'agudo';
                else if (tipo === 'preventivo') badgeClass = 'preventivo';
                let tipoTexto = tipo.charAt(0).toUpperCase() + tipo.slice(1);
                const desc = d.descripcion || '';
                const fechaCita = d.fecha_cita ? escapeHtml(d.fecha_cita) : '—';

                html += `
                    <tr data-tipo="${tipo}">
                        <td>
                            <div class="diagnostico-nombre">${escapeHtml(d.nombre)}</div>
                            <div class="diagnostico-desc">${escapeHtml(desc.substring(0, 100))}${desc.length > 100 ? '…' : ''}</div>
                        </td>
                        <td><span class="tipo-badge ${badgeClass}">${tipoTexto}</span></td>
                        <td>
                            <div class="medico-nombre">${escapeHtml(d.especialista)}</div>
                            <div class="medico-especialidad">${escapeHtml(d.servicio)}</div>
                        </td>
                        <td class="fecha-cell">${escapeHtml(d.fecha || '—')}</td>
                        <td class="fecha-cell">${fechaCita}</td>
                        <td class="actions-cell">
                            <button class="btn-ver" onclick="verDetalle(${d.id})">Ver detalle</button>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        function verDetalle(id) {
            const diag = diagnosticosData.find(d => d.id === id);
            if (!diag) return;

            const tipo = diag.tipo || 'preventivo';
            let tipoTexto = tipo.charAt(0).toUpperCase() + tipo.slice(1);
            let badgeClass = '';
            if (tipo === 'cronico') badgeClass = 'cronico';
            else if (tipo === 'agudo') badgeClass = 'agudo';
            else if (tipo === 'preventivo') badgeClass = 'preventivo';

            document.getElementById('modalTitulo').innerHTML = `<i class="fas fa-stethoscope"></i> ${escapeHtml(diag.nombre)}`;
            document.getElementById('modalBody').innerHTML = `
                <div class="detail-section">
                    <div class="detail-row">
                        <div class="detail-label">Tipo</div>
                        <div class="detail-value"><span class="tipo-badge ${badgeClass}">${tipoTexto}</span></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Fecha del diagnóstico</div>
                        <div class="detail-value">${escapeHtml(diag.fecha || '—')}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Cita agendada</div>
                        <div class="detail-value">${diag.fecha_cita ? escapeHtml(diag.fecha_cita) : 'No vinculada a una cita'}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Médico tratante</div>
                        <div class="detail-value">${escapeHtml(diag.especialista)} · ${escapeHtml(diag.servicio)}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Descripción</div>
                        <div class="detail-value">${escapeHtml(diag.descripcion || '')}</div>
                    </div>
                </div>
            `;
            document.getElementById('modalOverlay').classList.add('active');
        }

        function limpiarFiltros() {
            document.getElementById('filtroTipo').value = 'todos';
            document.getElementById('searchDiagnostico').value = '';
            activeFilter = 'todos';
            renderTable();
        }

        function cerrarModal() {
            document.getElementById('modalOverlay').classList.remove('active');
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

        // Event listeners
        document.getElementById('filtroTipo')?.addEventListener('change', function() {
            activeFilter = this.value;
            renderTable();
        });

        document.getElementById('searchDiagnostico')?.addEventListener('input', renderTable);
        document.getElementById('limpiarFiltros')?.addEventListener('click', limpiarFiltros);
        document.getElementById('modalOverlay')?.addEventListener('click', function(e) {
            if (e.target === this) cerrarModal();
        });

        renderTable();
    </script>

</body>
</html>