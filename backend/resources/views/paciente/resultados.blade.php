<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Mis Resultados</title>
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
                <h1>Mis <span>Resultados</span></h1>
                <p>Resultados de tus exámenes y estudios médicos</p>
            </div>

            {{-- FILTROS CON SELECT --}}
            <div class="filters-bar">
                <div class="filtros-left">
                    <div class="select-group">
                        <label><i class="fas fa-filter"></i> Tipo:</label>
                        <select id="filtroTipo" class="filtro-select">
                            <option value="todos">Todos los resultados</option>
                            <option value="laboratorio">Laboratorio</option>
                            <option value="imagen">Imágenes</option>
                            <option value="electrocardiograma">ECG</option>
                        </select>
                    </div>
                </div>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchResultado" placeholder="Buscar por examen, médico...">
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
                        <div class="stat-label">Total resultados</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-flask"></i></div>
                    <div>
                        <div class="stat-number" id="laboratorioCount">0</div>
                        <div class="stat-label">Laboratorio</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-image"></i></div>
                    <div>
                        <div class="stat-number" id="imagenCount">0</div>
                        <div class="stat-label">Imágenes</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-heartbeat"></i></div>
                    <div>
                        <div class="stat-number" id="ecgCount">0</div>
                        <div class="stat-label">ECG</div>
                    </div>
                </div>
            </div>

            {{-- TABLA DE RESULTADOS --}}
            <div class="table-container">
                <table class="resultados-table" id="tablaResultados">
                    <thead>
                        <tr>
                            <th>Examen</th>
                            <th>Tipo</th>
                            <th>Médico / especialidad</th>
                            <th>Fecha</th>
                            <th>Estado</th>
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
        const resultadosData = @json($resultadosForJs ?? []);

        let activeFilter = 'todos';

        function renderTable() {
            const searchTerm = document.getElementById('searchResultado')?.value.toLowerCase() || '';
            let filtered = [...resultadosData];

            if (activeFilter !== 'todos') {
                filtered = filtered.filter(d => d.tipo === activeFilter);
            }

            if (searchTerm !== '') {
                filtered = filtered.filter(d =>
                    d.nombre.toLowerCase().includes(searchTerm) ||
                    d.especialista.toLowerCase().includes(searchTerm) ||
                    d.servicio.toLowerCase().includes(searchTerm)
                );
            }

            // Actualizar estadísticas
            document.getElementById('totalCount').innerText = String(resultadosData.length);
            document.getElementById('laboratorioCount').innerText = String(resultadosData.filter(d => d.tipo === 'laboratorio').length);
            document.getElementById('imagenCount').innerText = String(resultadosData.filter(d => d.tipo === 'imagen').length);
            document.getElementById('ecgCount').innerText = String(resultadosData.filter(d => d.tipo === 'electrocardiograma').length);

            const tbody = document.getElementById('tableBody');
            if (filtered.length === 0) {
                tbody.innerHTML = `<tr class="empty-row"><td colspan="6">No se encontraron resultados con los filtros seleccionados.</td></tr>`;
                return;
            }

            let html = '';
            filtered.forEach(d => {
                let badgeClass = '';
                let tipoTexto = '';
                if (d.tipo === 'laboratorio') {
                    badgeClass = 'laboratorio';
                    tipoTexto = 'Laboratorio';
                } else if (d.tipo === 'imagen') {
                    badgeClass = 'imagen';
                    tipoTexto = 'Imagen';
                } else if (d.tipo === 'electrocardiograma') {
                    badgeClass = 'ecg';
                    tipoTexto = 'ECG';
                }

                let estadoClass = '';
                let estadoTexto = '';
                if (d.estado === 'normal') {
                    estadoClass = 'normal';
                    estadoTexto = 'Normal';
                } else if (d.estado === 'atencion') {
                    estadoClass = 'atencion';
                    estadoTexto = 'Requiere atención';
                } else {
                    estadoClass = 'anormal';
                    estadoTexto = 'Anormal';
                }

                html += `
                    <tr data-tipo="${d.tipo}">
                        <td>
                            <div class="examen-nombre">${escapeHtml(d.nombre)}</div>
                            <div class="examen-desc">${escapeHtml((d.descripcion || '').substring(0, 80))}${(d.descripcion || '').length > 80 ? '…' : ''}</div>
                        </td>
                        <td><span class="tipo-badge ${badgeClass}">${tipoTexto}</span></td>
                        <td>
                            <div class="medico-nombre">${escapeHtml(d.especialista)}</div>
                            <div class="medico-especialidad">${escapeHtml(d.servicio)}</div>
                        </td>
                        <td class="fecha-cell">${d.fecha}</td>
                        <td><span class="estado-badge ${estadoClass}">${estadoTexto}</span></td>
                        <td class="actions-cell">
                            <button class="btn-ver" onclick="verDetalle(${d.id})">Ver detalle</button>
                            <button class="btn-pdf" onclick="descargarPDF(${d.id})">📄 PDF</button>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        function verDetalle(id) {
            const result = resultadosData.find(r => r.id === id);
            if (!result) return;

            let tipoTexto = '';
            if (result.tipo === 'laboratorio') tipoTexto = 'Laboratorio';
            else if (result.tipo === 'imagen') tipoTexto = 'Imagen';
            else if (result.tipo === 'electrocardiograma') tipoTexto = 'ECG';

            let estadoClass = '';
            let estadoTexto = '';
            if (result.estado === 'normal') {
                estadoClass = 'normal';
                estadoTexto = 'Normal';
            } else if (result.estado === 'atencion') {
                estadoClass = 'atencion';
                estadoTexto = 'Requiere atención';
            } else {
                estadoClass = 'anormal';
                estadoTexto = 'Anormal';
            }

            document.getElementById('modalTitulo').innerHTML = `<i class="fas fa-flask"></i> ${escapeHtml(result.nombre)}`;
            document.getElementById('modalBody').innerHTML = `
                <div class="detail-section">
                    <div class="detail-row">
                        <div class="detail-label">Tipo</div>
                        <div class="detail-value">${tipoTexto}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Fecha</div>
                        <div class="detail-value">${result.fecha}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Médico tratante</div>
                        <div class="detail-value">${escapeHtml(result.especialista)} · ${escapeHtml(result.servicio)}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Estado</div>
                        <div class="detail-value"><span class="estado-badge ${estadoClass}">${estadoTexto}</span></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Resultados</div>
                        <div class="detail-value">${escapeHtml(result.detalle)}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Descripción</div>
                        <div class="detail-value">${escapeHtml(result.descripcion)}</div>
                    </div>
                </div>
            `;
            document.getElementById('modalOverlay').classList.add('active');
        }

        function descargarPDF(id) {
            alert(`Descargando PDF del resultado ID: ${id}`);
            // Aquí iría la lógica para descargar PDF
        }

        function limpiarFiltros() {
            document.getElementById('filtroTipo').value = 'todos';
            document.getElementById('searchResultado').value = '';
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

        document.getElementById('searchResultado')?.addEventListener('input', renderTable);
        document.getElementById('limpiarFiltros')?.addEventListener('click', limpiarFiltros);
        document.getElementById('modalOverlay')?.addEventListener('click', function(e) {
            if (e.target === this) cerrarModal();
        });

        renderTable();
    </script>

</body>
</html>