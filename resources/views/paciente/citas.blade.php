<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita</title>
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
            <a href="{{ route('paciente.dashboard') }}" class="btn-back"> Volver</a>
        </div>
    </nav>

    <div class="main">
        <div class="citas-wrapper">

            {{-- CALENDARIO --}}
            <div class="calendario-panel">
                <div class="panel-header">
                    <div class="panel-title">📅 Selecciona una fecha</div>
                </div>

                <div class="calendar">
                    <div class="calendar-header">
                        <button onclick="cambiarMes(-1)" class="cal-nav">‹</button>
                        <span id="mesAnio"></span>
                        <button onclick="cambiarMes(1)" class="cal-nav">›</button>
                    </div>
                    <div class="calendar-days">
                        <span>Dom</span><span>Lun</span><span>Mar</span>
                        <span>Mié</span><span>Jue</span><span>Vie</span><span>Sáb</span>
                    </div>
                    <div class="calendar-grid" id="calendarGrid"></div>
                </div>

                {{-- HORARIOS --}}
                <div class="horarios-section" id="horariosSection" style="display:none">
                    <div class="section-label">Selecciona un horario</div>
                    <div class="horarios-grid" id="horariosGrid"></div>
                </div>
            </div>

            {{-- FORMULARIO --}}
            <div class="form-panel">
                <div class="panel-header">
                    <div class="panel-title">📋 Datos de la cita</div>
                </div>

                <div class="resumen-fecha" id="resumenFecha" style="display:none">
                    <div class="resumen-icon"></div>
                    <div>
                        <div class="resumen-fecha-txt" id="resumenFechaTxt"></div>
                        <div class="resumen-hora-txt" id="resumenHoraTxt"></div>
                    </div>
                </div>

                <form id="formCita">
                    <div class="form-group">
                        <label class="form-label">Médico</label>
                        <select class="form-select" id="medicoSelect">
                            <option value="">Selecciona un médico</option>
                            <option value="1">Dr. Ramírez López - Cardiología</option>
                            <option value="2">Dra. Torres Vega - Medicina General</option>
                            <option value="3">Dr. Herrera Cruz - Neurología</option>
                            <option value="4">Dra. Silva Mora - Pediatría</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Sala</label>
                        <select class="form-select" id="salaSelect">
                            <option value="">Selecciona una sala</option>
                            <option value="1">Sala 101 - Piso 1</option>
                            <option value="2">Sala 202 - Piso 2</option>
                            <option value="3">Sala 303 - Piso 3</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tipo de consulta</label>
                        <select class="form-select" id="tipoSelect">
                            <option value="">Selecciona el tipo</option>
                            <option value="consulta">Consulta general</option>
                            <option value="seguimiento">Seguimiento</option>
                            <option value="urgencia">Urgencia</option>
                            <option value="especialidad">Especialidad</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Motivo de la consulta</label>
                        <textarea class="form-textarea" id="motivoInput" placeholder="Describe brevemente el motivo de tu consulta..." rows="4"></textarea>
                    </div>

                    <button type="button" onclick="agendarCita()" class="btn-agendar" id="btnAgendar">
                         Confirmar cita
                    </button>
                </form>

                {{-- CONFIRMACION --}}
                <div class="confirmacion" id="confirmacion" style="display:none">
                    <div class="confirmacion-icon"></div>
                    <h3>¡Cita agendada!</h3>
                    <p>Tu cita ha sido registrada exitosamente.</p>
                    <div class="confirmacion-detalle" id="confirmacionDetalle"></div>
                    <a href="{{ route('paciente.dashboard') }}" class="btn-volver">Volver al inicio</a>
                </div>
            </div>

        </div>
    </div>

    <script>
        let fechaSeleccionada = null;
        let horaSeleccionada = null;
        let currentDate = new Date();

        const meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        const horarios = ['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','14:00','14:30','15:00','15:30','16:00','16:30'];

        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            document.getElementById('mesAnio').textContent = `${meses[month]} ${year}`;

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const today = new Date();

            let html = '';
            for (let i = 0; i < firstDay; i++) html += '<div></div>';

            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(year, month, day);
                const isPast = date < new Date(today.getFullYear(), today.getMonth(), today.getDate());
                const isWeekend = date.getDay() === 0 || date.getDay() === 6;
                const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
                const isSelected = fechaSeleccionada === dateStr;

                let cls = 'cal-day';
                if (isPast || isWeekend) cls += ' disabled';
                else cls += ' available';
                if (isSelected) cls += ' selected';

                html += `<div class="${cls}" onclick="${(!isPast && !isWeekend) ? `selectFecha('${dateStr}', ${day}, '${meses[month]}', ${year})` : ''}">${day}</div>`;
            }

            document.getElementById('calendarGrid').innerHTML = html;
        }

        function cambiarMes(dir) {
            currentDate.setMonth(currentDate.getMonth() + dir);
            renderCalendar();
        }

        function selectFecha(dateStr, day, mes, year) {
            fechaSeleccionada = dateStr;
            horaSeleccionada = null;
            renderCalendar();

            const horariosSection = document.getElementById('horariosSection');
            horariosSection.style.display = 'block';

            let html = '';
            horarios.forEach(h => {
                const ocupado = Math.random() < 0.3;
                html += `<div class="horario-btn ${ocupado ? 'ocupado' : ''}" onclick="${!ocupado ? `selectHora('${h}', '${day}', '${mes}', '${year}')` : ''}">${h}</div>`;
            });
            document.getElementById('horariosGrid').innerHTML = html;
        }

        function selectHora(hora, day, mes, year) {
            horaSeleccionada = hora;
            document.querySelectorAll('.horario-btn').forEach(b => b.classList.remove('selected'));
            event.target.classList.add('selected');

            document.getElementById('resumenFecha').style.display = 'flex';
            document.getElementById('resumenFechaTxt').textContent = `${day} de ${mes} de ${year}`;
            document.getElementById('resumenHoraTxt').textContent = ` ${hora} hrs`;
        }

        function agendarCita() {
            const medico = document.getElementById('medicoSelect').value;
            const sala = document.getElementById('salaSelect').value;
            const tipo = document.getElementById('tipoSelect').value;
            const motivo = document.getElementById('motivoInput').value;

            if (!fechaSeleccionada) return alert('Selecciona una fecha');
            if (!horaSeleccionada) return alert('Selecciona un horario');
            if (!medico) return alert('Selecciona un médico');
            if (!sala) return alert('Selecciona una sala');
            if (!tipo) return alert('Selecciona el tipo de consulta');
            if (!motivo) return alert('Escribe el motivo de la consulta');

            document.getElementById('formCita').style.display = 'none';
            document.getElementById('confirmacion').style.display = 'block';

            const medicoNombre = document.getElementById('medicoSelect').options[document.getElementById('medicoSelect').selectedIndex].text;
            const salaNombre = document.getElementById('salaSelect').options[document.getElementById('salaSelect').selectedIndex].text;

            document.getElementById('confirmacionDetalle').innerHTML = `
                <p> Fecha: <strong>${fechaSeleccionada} a las ${horaSeleccionada}</strong></p>
                <p> Médico: <strong>${medicoNombre}</strong></p>
                <p> Sala: <strong>${salaNombre}</strong></p>
                <p> Tipo: <strong>${tipo}</strong></p>
                <p> Motivo: <strong>${motivo}</strong></p>
            `;
        }

        renderCalendar();
    </script>

</body>
</html>