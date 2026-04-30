<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Recetas | Clínica Salud</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
   <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="dash">

    {{-- NAVBAR consistente --}}
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
                <div class="user-name">{{ $usuario->nombre ?? 'Valentino' }}</div>
                <div class="user-role">PACIENTE</div>
            </div>
            <div class="avatar">{{ strtoupper(substr($usuario->nombre ?? 'V', 0, 1)) }}</div>
            <a href="{{ route('paciente.dashboard') }}" class="btn-back"> Volver</a>
        </div>
    </nav>

    <div class="main">
        <div class="content-wrapper">

            {{-- HEADER PÁGINA --}}
            <div class="page-header-simple">
                <div class="page-header-icon" style="background:#faeeda;">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#854f0b" stroke-width="1.8">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 5h6M12 12v4M10 14h4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="page-title-text">Mis Recetas Médicas</h2>
                    <p class="page-subtitle">Historial completo de tus prescripciones y tratamientos</p>
                </div>
            </div>

            {{-- FILTROS Y BUSCADOR UNIFICADOS (todo en uno) --}}
            <div class="filters-bar">
                <div class="filter-group">
                    <select id="filterSelect" class="filter-select">
                        <option value="todas">Todas las recetas</option>
                        <option value="activa">Activas</option>
                        <option value="completada">Completadas</option>
                        <option value="vencida">Vencidas</option>
                    </select>
                </div>
                <div class="search-box">
                    <input type="text" id="searchReceta" placeholder="Buscar medicamento, médico...">
                </div>
            </div>

            {{-- TABLA DE RECETAS (estilo profesional) --}}
            <div class="table-container">
                <table class="recetas-table">
                    <thead>
                        <tr>
                            <th>Medicamento / tratamiento</th>
                            <th>Estado</th>
                            <th>Médico / especialidad</th>
                            <th>Fecha emisión</th>
                            <th>Vencimiento</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        {{-- Datos dinámicos desde JS --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL DETALLE --}}
    <div class="modal-overlay" id="detailModal">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h4 id="modalTitulo">Detalle de receta</h4>
                <button class="modal-close" onclick="closeModal()">✕</button>
            </div>
            <div class="modal-body" id="modalBody"></div>
            <div class="modal-footer">
                <button class="btn-imprimir-modal" onclick="imprimirReceta()">Imprimir receta</button>
            </div>
        </div>
    </div>

    <script>
        // Datos de recetas
        const recetasData = [
            {
                id: 1,
                medicamento: "Losartán 50mg",
                indicaciones: "Tomar 1 tableta cada 24 horas con agua. No suspender sin consultar al médico. Controlar presión semanalmente.",
                estado: "activa",
                medico: "Dr. Ramírez López",
                especialidad: "Cardiología",
                fechaEmision: "15 Mar 2026",
                fechaVencimiento: "15 Jun 2026",
                cantidad: "30 tabletas",
                presentacion: "Comprimidos",
                dosis: "1 comprimido cada 24h"
            },
            {
                id: 2,
                medicamento: "Amoxicilina 500mg",
                indicaciones: "Tomar 1 cápsula cada 8 horas por 7 días. Completar el tratamiento aunque mejore los síntomas.",
                estado: "activa",
                medico: "Dra. Torres Vega",
                especialidad: "Medicina General",
                fechaEmision: "02 Abr 2026",
                fechaVencimiento: "09 Abr 2026",
                cantidad: "21 cápsulas",
                presentacion: "Cápsulas",
                dosis: "1 cada 8 horas"
            },
            {
                id: 3,
                medicamento: "Ibuprofeno 400mg",
                indicaciones: "Tomar 1 tableta cada 8 horas con alimentos por 5 días. No exceder la dosis recomendada.",
                estado: "completada",
                medico: "Dr. Herrera Cruz",
                especialidad: "Medicina Preventiva",
                fechaEmision: "10 Ene 2026",
                fechaVencimiento: "15 Ene 2026",
                cantidad: "15 tabletas",
                presentacion: "Comprimidos",
                dosis: "1 cada 8 horas"
            },
            {
                id: 4,
                medicamento: "Metformina 850mg",
                indicaciones: "Tomar 1 tableta cada 12 horas con las comidas principales. Monitorear niveles de glucosa.",
                estado: "activa",
                medico: "Dra. Isabel Montero",
                especialidad: "Endocrinología",
                fechaEmision: "20 Mar 2026",
                fechaVencimiento: "20 Jun 2026",
                cantidad: "60 tabletas",
                presentacion: "Comprimidos",
                dosis: "1 cada 12 horas"
            },
            {
                id: 5,
                medicamento: "Omeprazol 20mg",
                indicaciones: "Tomar 1 cápsula en ayunas, 30 minutos antes del desayuno, por 14 días.",
                estado: "completada",
                medico: "Dr. Mauricio Peña",
                especialidad: "Gastroenterología",
                fechaEmision: "05 Feb 2026",
                fechaVencimiento: "19 Feb 2026",
                cantidad: "14 cápsulas",
                presentacion: "Cápsulas",
                dosis: "1 en ayunas"
            },
            {
                id: 6,
                medicamento: "Salbutamol Inhalador",
                indicaciones: "Aplicar 2 inhalaciones cada 6 horas si presenta dificultad respiratoria. No exceder 8 inhalaciones por día.",
                estado: "vencida",
                medico: "Dra. Lucía Fuentes",
                especialidad: "Neumología",
                fechaEmision: "01 Dic 2025",
                fechaVencimiento: "01 Mar 2026",
                cantidad: "1 inhalador (200 dosis)",
                presentacion: "Inhalador",
                dosis: "2 inhalaciones c/6h"
            }
        ];

        // Referencias DOM
        const tbody = document.getElementById('tableBody');
        const searchInput = document.getElementById('searchReceta');
        let activeFilter = 'todos';
        let currentRecetaId = null;

        // Helper functions
        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
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

        function getEstadoBadgeClass(estado) {
            switch(estado) {
                case 'activa': return 'activa';
                case 'completada': return 'completada';
                case 'vencida': return 'vencida';
                default: return '';
            }
        }

        function getEstadoTexto(estado) {
            switch(estado) {
                case 'activa': return 'ACTIVA';
                case 'completada': return 'COMPLETADA';
                case 'vencida': return 'VENCIDA';
                default: return estado.toUpperCase();
            }
        }

        // Renderizar tabla
        function renderTable() {
            const searchTerm = searchInput.value.trim().toLowerCase();
            let filtered = [...recetasData];

            if (activeFilter !== 'todos') {
                filtered = filtered.filter(r => r.estado === activeFilter);
            }

            if (searchTerm !== '') {
                filtered = filtered.filter(r =>
                    r.medicamento.toLowerCase().includes(searchTerm) ||
                    r.medico.toLowerCase().includes(searchTerm) ||
                    r.especialidad.toLowerCase().includes(searchTerm) ||
                    r.indicaciones.toLowerCase().includes(searchTerm)
                );
            }

            if (filtered.length === 0) {
                tbody.innerHTML = `<tr class="empty-row"><td colspan="6">No se encontraron recetas con los filtros seleccionados.</td></tr>`;
                return;
            }

            let html = '';
            filtered.forEach(r => {
                const badgeClass = getEstadoBadgeClass(r.estado);
                const estadoTexto = getEstadoTexto(r.estado);
                
                let vencimientoClass = '';
                if (r.estado === 'vencida') vencimientoClass = 'vencido-date';
                
                html += `
                    <tr data-id="${r.id}" data-estado="${r.estado}">
                        <td>
                            <div class="medicamento-nombre">${escapeHtml(r.medicamento)}</div>
                            <div class="medicamento-dosis">${escapeHtml(r.dosis)} · ${escapeHtml(r.presentacion)}</div>
                        </td>
                        <td><span class="estado-badge ${badgeClass}">${estadoTexto}</span></td>
                        <td>
                            <div>${escapeHtml(r.medico)}</div>
                            <div class="especialidad-text">${escapeHtml(r.especialidad)}</div>
                        </td>
                        <td class="fecha-cell">${r.fechaEmision}</td>
                        <td class="fecha-cell ${vencimientoClass}">${r.fechaVencimiento}</td>
                        <td class="actions-cell">
                            <button class="btn-ver" onclick="verDetalleReceta(${r.id})">Ver detalle</button>
                            <button class="btn-imprimir" onclick="imprimirRecetaId(${r.id})">🖨️</button>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        // Ver detalle de receta
        window.verDetalleReceta = function(id) {
            const receta = recetasData.find(r => r.id === id);
            if (!receta) return;
            currentRecetaId = id;
            
            document.getElementById('modalTitulo').innerHTML = `<span class="modal-icon"></span> ${escapeHtml(receta.medicamento)}`;
            
            const estadoTexto = getEstadoTexto(receta.estado);
            const badgeClass = getEstadoBadgeClass(receta.estado);
            
            document.getElementById('modalBody').innerHTML = `
                <div class="detail-section">
                    <div class="detail-row">
                        <div class="detail-label">Estado</div>
                        <div class="detail-value"><span class="estado-badge ${badgeClass}">${estadoTexto}</span></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Médico tratante</div>
                        <div class="detail-value">${escapeHtml(receta.medico)} · ${escapeHtml(receta.especialidad)}</div>
                    </div>
                    <div class="detail-row dual-column">
                        <div>
                            <div class="detail-label">Fecha de emisión</div>
                            <div class="detail-value">${receta.fechaEmision}</div>
                        </div>
                        <div>
                            <div class="detail-label">Fecha de vencimiento</div>
                            <div class="detail-value ${receta.estado === 'vencida' ? 'text-danger' : ''}">${receta.fechaVencimiento}</div>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Posología / dosis</div>
                        <div class="detail-value">${escapeHtml(receta.dosis)}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Presentación / cantidad</div>
                        <div class="detail-value">${escapeHtml(receta.presentacion)} · ${escapeHtml(receta.cantidad)}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Indicaciones médicas</div>
                        <div class="detail-value indicaciones-text">${escapeHtml(receta.indicaciones)}</div>
                    </div>
                </div>
            `;
            document.getElementById('detailModal').classList.add('active');
        };

        // Imprimir receta
        window.imprimirReceta = function() {
            if (currentRecetaId) {
                imprimirRecetaId(currentRecetaId);
            }
        };

        window.imprimirRecetaId = function(id) {
            const receta = recetasData.find(r => r.id === id);
            if (!receta) return;
            
            const ventanaImpresion = window.open('', '_blank');
            ventanaImpresion.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Receta Médica - ${receta.medicamento}</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 40px; max-width: 600px; margin: 0 auto; }
                        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
                        .receta-title { font-size: 24px; color: #2c5f8a; }
                        .medicamento { font-size: 20px; font-weight: bold; margin: 20px 0; color: #1a5fa8; }
                        .info-row { margin: 12px 0; }
                        .label { font-weight: bold; color: #555; }
                        .indicaciones { margin-top: 25px; padding: 15px; background: #f5f5f5; border-radius: 8px; }
                        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #888; border-top: 1px solid #ddd; padding-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h2>Clínica Salud</h2>
                        <p>Receta Médica Oficial</p>
                    </div>
                    <div class="medicamento">${receta.medicamento}</div>
                    <div class="info-row"><span class="label">Paciente:</span> ${document.querySelector('.user-name')?.innerText || '___________________'}</div>
                    <div class="info-row"><span class="label">Médico:</span> ${receta.medico} (${receta.especialidad})</div>
                    <div class="info-row"><span class="label">Fecha emisión:</span> ${receta.fechaEmision}</div>
                    <div class="info-row"><span class="label">Vencimiento:</span> ${receta.fechaVencimiento}</div>
                    <div class="info-row"><span class="label">Dosis/posología:</span> ${receta.dosis}</div>
                    <div class="info-row"><span class="label">Presentación:</span> ${receta.presentacion} - ${receta.cantidad}</div>
                    <div class="indicaciones">
                        <strong>Indicaciones:</strong><br>
                        ${receta.indicaciones}
                    </div>
                    <div class="footer">
                        <p>Este documento es una receta médica válida. Presentar en farmacia para su dispensación.</p>
                        <p>Firma del médico: ___________________</p>
                    </div>
                    <script>window.print();<\/script>
                </body>
                </html>
            `);
            ventanaImpresion.document.close();
        };

        window.closeModal = function() {
            document.getElementById('detailModal').classList.remove('active');
            currentRecetaId = null;
        };

        // Event listeners
        document.querySelectorAll('.filter-chip').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-chip').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                activeFilter = this.getAttribute('data-filter');
                renderTable();
            });
        });

        searchInput.addEventListener('input', () => renderTable());

        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // Inicializar
        renderTable();
    </script>

</body>
</html>