<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Excelencia en Análisis Clínicos</title>
    <link rel="stylesheet" href="{{ mix('css/landing.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

{{-- ============================== NAVBAR ============================== --}}
<nav class="navbar">
    <div class="navbar__container">
        <div class="navbar__logo">
            <div class="navbar__logo-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </div>
            <div class="navbar__logo-text">
                <span class="navbar__logo-main">E&M</span>
                <span class="navbar__logo-sub">Laboratorio</span>
            </div>
        </div>

        <div class="navbar__links">
            <a href="#nosotros">Nosotros</a>
            <a href="#especialidades">Laboratorio Clínico</a>
            <a href="#contacto">Contacto</a>
        </div>

        <div class="navbar__actions">
            <div class="navbar__socials">
                <a href="https://www.instagram.com/essaludperu/" target="_blank" rel="noopener noreferrer" class="navbar__social-icon">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://www.facebook.com/makanaki.larealeza" target="_blank" rel="noopener noreferrer" class="navbar__social-icon">
                    <i class="fab fa-facebook-f"></i>
                </a>
            </div>
            <a href="{{ route('login') }}" class="btn btn--filled">Resultados en línea</a>
            <a href="{{ route('paciente.citas') }}" class="btn btn--outline">Agenda una cita</a>
        </div>
    </div>
</nav>

{{-- ============================== HERO ============================== --}}
<section class="hero">
    <div class="hero__bg-image"></div>
    <div class="hero__overlay"></div>

    {{-- Iconos flotantes animados --}}
<div class="hero__floats" aria-hidden="true">
    <div class="float-icon float-icon--1">
        <div class="float-bubble">
            <i class="fas fa-tint"></i>
        </div>
    </div>
    <div class="float-icon float-icon--2">
        <div class="float-bubble">
            <i class="fas fa-flask"></i>
        </div>
    </div>
    <div class="float-icon float-icon--3">
        <div class="float-bubble">
            <i class="fas fa-microscope"></i>
        </div>
    </div>
    <div class="float-icon float-icon--4">
        <div class="float-bubble">
            <i class="fas fa-dna"></i>
        </div>
    </div>
    <div class="float-icon float-icon--5">
        <div class="float-bubble">
            <i class="fas fa-shield-virus"></i>
        </div>
    </div>
    <div class="float-icon float-icon--6">
        <div class="float-bubble">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>
    <div class="float-icon float-icon--7">
        <div class="float-bubble">
            <i class="fas fa-skull-crossbones"></i>
        </div>
    </div>
</div>
    </div>

    <div class="hero__content">
        <h1 class="hero__title">Nuestra experiencia<br>es la diferencia</h1>
        <ul class="hero__bullets">
            <li><i class="fas fa-circle-check"></i> Resultados 100% confiables</li>
            <li><i class="fas fa-circle-check"></i> +1000 pacientes satisfechos</li>
            <li><i class="fas fa-circle-check"></i> Profesionales con más de 10 años de experiencia</li>
        </ul>
        <div class="hero__buttons">
            <a href="#especialidades" class="hero-btn hero-btn--primary">Laboratorio Clínico</a>
            <a href="#referencia" class="hero-btn hero-btn--primary">Laboratorio de Referencia</a>
            <a href="#genetica" class="hero-btn hero-btn--primary">Laboratorio Genético</a>
        </div>
    </div>
</section>

{{-- ============================== ESPECIALIDADES ============================== --}}
<section class="especialidades" id="especialidades">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Nuestras especialidades</h2>
        </div>

        <div class="esp-grid">
            <div class="esp-card">
                <div class="esp-card__icon"><i class="bi bi-droplet-fill"></i></div>
                <p class="esp-card__name">Hematología</p>
            </div>
            <div class="esp-card">
                <div class="esp-card__icon"><i class="fas fa-flask"></i></div>
                <p class="esp-card__name">Bioquímica</p>
            </div>
            <div class="esp-card">
                <div class="esp-card__icon"><i class="fas fa-shield-virus"></i></div>
                <p class="esp-card__name">Inmunología Básica</p>
            </div>
            <div class="esp-card">
                <div class="esp-card__icon"><i class="fas fa-virus"></i></div>
                <p class="esp-card__name">Inmunología Especial</p>
            </div>
            <div class="esp-card">
                <div class="esp-card__icon"><i class="fas fa-skull-crossbones"></i></div>
                <p class="esp-card__name">Toxicología</p>
            </div>
            <div class="esp-card">
                <div class="esp-card__icon"><i class="fas fa-microscope"></i></div>
                <p class="esp-card__name">Microbiología</p>
            </div>
            <div class="esp-card">
                <div class="esp-card__icon"><i class="fas fa-dna"></i></div>
                <p class="esp-card__name">Pruebas Genéticas</p>
            </div>
            <div class="esp-card">
                <div class="esp-card__icon"><i class="bi bi-graph-up"></i></div>
                <p class="esp-card__name">Pruebas Moleculares</p>
            </div>
        </div>
    </div>
</section>

{{-- ============================== SEDES ============================== --}}
<section class="sedes" id="sedes">
    <div class="container sedes__inner">
        <div class="sedes__map">
            <iframe 
                src="https://www.google.com/maps/d/u/1/embed?mid=1PxGqMs3zImKGmNY3h2kfr7m7AAlj-IU&ehbc=2E312F"
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
        <div class="sedes__info">
            <div class="section-header section-header--left">
                <h2 class="section-title">Nuestras Sedes</h2>
            </div>

            @php
            $sedes = [
                ['nombre' => 'Los Olivos',          'dir' => 'Av. Antúnez de Mayolo 1515 2do Piso'],
                ['nombre' => 'Cercado de Lima',     'dir' => 'Av. La Alborada 1709'],
                ['nombre' => 'Pueblo Libre',        'dir' => 'Av. Brasil 2730, Consultorio 515 Edificio médico QUALIS'],
                ['nombre' => 'Magdalena',           'dir' => 'Av. Antonio José de Sucre 773'],
                ['nombre' => 'San Martín de Porres','dir' => 'Av. Honorio Delgado 206, Centro Médico VASA SALUD'],
            ];
            @endphp

            @foreach($sedes as $sede)
            <div class="sede-item">
                <span class="sede-item__dot"></span>
                <div>
                    <p class="sede-item__name">{{ $sede['nombre'] }}</p>
                    <p class="sede-item__dir">{{ $sede['dir'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================== NOSOTROS BANNER ============================== --}}
<section class="nosotros" id="nosotros">
    <div class="nosotros__body">
        <div class="nosotros__text">
            <h2 class="nosotros__title">Más de 10 años de experiencia</h2>
            <p class="nosotros__desc">
                Somos un laboratorio especializado en análisis clínicos cuyo objetivo es
                <strong>OFRECER UN SERVICIO INTEGRAL</strong> que cubra todas las
                necesidades y expectativas de nuestros clientes.
            </p>
            <ul class="nosotros__bullets">
                <li><span class="bullet-check">✓</span> Equipo humano altamente calificado</li>
                <li><span class="bullet-check">✓</span> Instalaciones de la más avanzada tecnología</li>
                <li><span class="bullet-check">✓</span> Equipos e instrumentos de última generación</li>
            </ul>
        </div>
        <a href="{{ route('paciente.citas') }}" class="btn-cta">Agenda tu cita</a>
    </div>
</section>

{{-- ============================== POR QUÉ ============================== --}}
<section class="porque" id="porque">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">¿Por qué <span>confiar en nosotros</span>?</h2>
            <p class="section-subtitle">Calidad, tecnología y compromiso con tu salud</p>
        </div>

        <div class="porque__grid">
            <div class="porque-card">
                <div class="porque-card__icon">
                    <i class="fas fa-home"></i>
                </div>
                <h3 class="porque-card__title">Atención a Domicilio</h3>
                <p class="porque-card__desc">Servicio 24/7 en la comodidad de tu hogar</p>
            </div>
            <div class="porque-card">
                <div class="porque-card__icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <h3 class="porque-card__title">Validación Médica</h3>
                <p class="porque-card__desc">Resultados revisados por médico patólogo</p>
            </div>
            <div class="porque-card">
                <div class="porque-card__icon">
                    <i class="fas fa-laptop"></i>
                </div>
                <h3 class="porque-card__title">Resultados en Línea</h3>
                <p class="porque-card__desc">Consulta tus resultados desde cualquier lugar</p>
            </div>
        </div>
    </div>
</section>

{{-- ============================== ALIADOS ============================== --}}
<section class="aliados">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title aliados__title">Ellos ya confiaron en nosotros</h2>
        </div>
        <div class="aliados__logos">
            <div class="aliado-bubble">MediVida<br><small>Policlínicos</small></div>
            <div class="aliado-bubble">Policlínico<br><small>Los Portales</small></div>
            <div class="aliado-bubble">Sanna</div>
            <div class="aliado-bubble">Consultorios<br><small>Mi Salud</small></div>
            <div class="aliado-bubble">Clínicas<br><small>Trailblazer</small></div>
        </div>
    </div>
</section>

{{-- ============================== FOOTER ============================== --}}
<footer class="footer" id="contacto">
    <div class="footer__nav-bar">
        <div class="footer__nav-links">
            <a href="#">Nosotros</a>
            <a href="#">Laboratorio Clínico</a>
            <a href="#">Laboratorio de Referencia</a>
            <a href="#">Contacto</a>
        </div>
        <div class="footer__nav-socials">
            <a href="https://www.facebook.com/makanaki.larealeza" target="_blank" rel="noopener noreferrer" class="footer__social">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://www.instagram.com/essaludperu/" target="_blank" rel="noopener noreferrer" class="footer__social">
                <i class="fab fa-instagram"></i>
            </a>
        </div>
    </div>

    <div class="footer__body">
      <div class="footer__brand">
    <div class="footer__logo">
        <div class="footer__logo-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
        </div>
        <div class="footer__logo-text">
            <span class="footer__logo-main">E&M</span>
            <span class="footer__logo-sub">LABORATORIO</span>
        </div>
    </div>
    <p class="footer__desc">
        E&M Laboratorio nace para brindar un servicio de calidad a Médicos y pacientes,
        ofreciendo resultados precisos en tiempos oportunos para el adecuado diagnóstico
        y/o tratamiento a seguir.
    </p>
</div>
        <div class="footer__contact-list">
            <h4>Contacto:</h4>
            <div class="footer__contact-item">
                <p>Av. Antunez de Mayolo 1515, Los Olivos. 2do Piso, (dentro del Policlínico Líder)</p>
                <a href="tel:+51958798987">+51 958 798 987</a>
            </div>
            <div class="footer__contact-item">
                <p>Av. La Alborada 1709, Cercado de Lima</p>
                <a href="tel:+51967482679">+51 967 482 679</a>
            </div>
            <div class="footer__contact-item">
                <p>Av. Antonio José de Sucre 773, Magdalena</p>
                <a href="tel:+51987597446">+51 987 597 446</a>
            </div>
            <div class="footer__contact-item">
                <p>Av. Brasil 2730, Consultorio 515 Edificio médico QUALIS, Pueblo Libre</p>
                <a href="tel:+51996448554">+51 996 448 554</a>
            </div>
            <div class="footer__contact-item">
                <p>Av. Honorio Delgado 206, CENTRO MEDICO VASA SALUD, San Martín de Porres</p>
                <a href="tel:+51954978455">+51 954 978 455</a>
            </div>
            <a href="mailto:eduardocaballero392@gmail.com" class="footer__email">informes@laboratoriE&M.pe</a>
        </div>

        <div class="footer__libro">
    <a href="#" class="footer__libro-link">
        <i class="fas fa-book"></i>
        <span>Libro de Reclamaciones</span>
    </a>
</div>
    </div>

    <div class="footer__bottom">
        <p>Copyright &copy; {{ date('Y') }} E&M Laboratorio - Resultados 100% confiables</p>
    </div>
</footer>

{{-- ============================== WHATSAPP FLOTANTE ============================== --}}
<a href="https://wa.me/51954987679?text=Hola%2C%20quiero%20más%20información%20sobre%20sus%20servicios%20de%20laboratorio" 
   class="whatsapp-float" 
   target="_blank" 
   rel="noopener noreferrer">
    <i class="fab fa-whatsapp"></i>
</a>

</body>

</html>