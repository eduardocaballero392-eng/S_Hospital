import React, { useState, useEffect } from 'react';

const Landing = () => {
    const [scrolled, setScrolled] = useState(false);

    useEffect(() => {
        const handleScroll = () => {
            setScrolled(window.scrollY > 50);
        };
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    const scrollTo = (id) => {
        const element = document.getElementById(id);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth' });
        }
    };

    const especialidades = [
        { icon: "🩸", name: "Hematología" },
        { icon: "🧪", name: "Bioquímica" },
        { icon: "🛡️", name: "Inmunología Básica" },
        { icon: "🔬", name: "Inmunología Especial" },
        { icon: "⚠️", name: "Toxicología" },
        { icon: "🦠", name: "Microbiología" },
        { icon: "🧬", name: "Pruebas Genéticas" },
        { icon: "📊", name: "Pruebas Moleculares" }
    ];

    const servicios = [
        { icon: "🚗", title: "Atención a Domicilio 24/7", description: "Te atendemos en tu hogar a cualquier hora del día" },
        { icon: "👨‍⚕️", title: "Validación por Médico Patólogo", description: "Cada resultado es revisado por especialistas" },
        { icon: "💻", title: "Resultados en Línea", description: "Consulta tus resultados desde cualquier lugar" }
    ];

    const nosotros = [
        { icon: "👨‍🔬", title: "Equipo altamente calificado", description: "Profesionales especializados en cada área de diagnóstico" },
        { icon: "🏥", title: "Tecnología avanzada", description: "Equipos e instrumentos de última generación" },
        { icon: "⚙️", title: "Procesos certificados", description: "Procedimientos bajo estándares internacionales" }
    ];

    return (
        <div className="landing-container">
            {/* NAVBAR */}
            <nav className={`navbar-landing ${scrolled ? 'scrolled' : ''}`}>
                <div className="nav-content">
                    <div className="logo">
                        <div className="logo-icon">🔬</div>
                        <div className="logo-text">
                            E&M <span>Laboratorio</span>
                        </div>
                    </div>
                    <div className="nav-links">
                        <a href="#inicio" onClick={(e) => { e.preventDefault(); scrollTo('inicio'); }}>Inicio</a>
                        <a href="#nosotros" onClick={(e) => { e.preventDefault(); scrollTo('nosotros'); }}>Nosotros</a>
                        <a href="#especialidades" onClick={(e) => { e.preventDefault(); scrollTo('especialidades'); }}>Especialidades</a>
                        <a href="#contacto" onClick={(e) => { e.preventDefault(); scrollTo('contacto'); }}>Contacto</a>
                    </div>
                </div>
            </nav>

            {/* HERO SECTION */}
            <section className="hero-section" id="inicio">
                <div className="hero-bg">
                    <div className="hero-bg-shape"></div>
                    <div className="hero-bg-shape2"></div>
                </div>
                <div className="hero-content">
                    <div className="hero-left">
                        <div className="hero-badge">Excelencia en Análisis Clínicos</div>
                        <h1>
                            Más de <span className="gradient-text">10 años</span><br />
                            de experiencia
                        </h1>
                        <p>
                            Somos un laboratorio especializado en análisis clínicos con estándares internacionales,
                            tecnología de punta y profesionales altamente calificados.
                        </p>
                        <div className="hero-buttons">
                            <button className="btn-primary" onClick={() => window.location.href = '/resultados'}>
                                📊 Resultados en línea
                            </button>
                            <button className="btn-secondary" onClick={() => window.location.href = '/citas'}>
                                📋 Agenda una cita
                            </button>
                        </div>
                        <div className="hero-stats">
                            <div className="stat">
                                <div className="stat-number">+10k</div>
                                <div className="stat-label">Pacientes atendidos</div>
                            </div>
                            <div className="stat">
                                <div className="stat-number">+20</div>
                                <div className="stat-label">Años de experiencia</div>
                            </div>
                            <div className="stat">
                                <div className="stat-number">100%</div>
                                <div className="stat-label">Resultados confiables</div>
                            </div>
                        </div>
                    </div>
                    <div className="hero-right">
                        <div className="floating-cards">
                            <div className="floating-card card-1">
                                <span>🔬</span> Laboratorio Clínico
                            </div>
                            <div className="floating-card card-2">
                                <span>🧬</span> E&M Genetics
                            </div>
                            <div className="floating-card card-3">
                                <span>📊</span> Laboratorio de Referencia
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* NOSOTROS SECTION */}
            <section className="nosotros-section" id="nosotros">
                <div className="container">
                    <div className="nosotros-header">
                        <h2>¿Por qué <span className="highlight">confiar en nosotros</span>?</h2>
                        <p>Ofrecemos servicios de laboratorio con los más altos estándares de calidad</p>
                    </div>
                    <div className="nosotros-grid">
                        {nosotros.map((item, idx) => (
                            <div key={idx} className="nosotros-card">
                                <div className="card-icon">{item.icon}</div>
                                <h3>{item.title}</h3>
                                <p>{item.description}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* ESPECIALIDADES SECTION */}
            <section className="especialidades-section" id="especialidades">
                <div className="container">
                    <h2 className="section-title">Nuestras <span className="highlight">especialidades</span></h2>
                    <div className="especialidades-grid">
                        {especialidades.map((item, idx) => (
                            <div key={idx} className="especialidad-card">
                                <span className="especialidad-icon">{item.icon}</span> {item.name}
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* SERVICIOS SECTION */}
            <section className="servicios-section">
                <div className="container">
                    <div className="servicios-grid">
                        {servicios.map((item, idx) => (
                            <div key={idx} className="servicio-card">
                                <div className="servicio-icon">{item.icon}</div>
                                <h3>{item.title}</h3>
                                <p>{item.description}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* FOOTER */}
            <footer className="footer" id="contacto">
                <div className="footer-container">
                    <div className="footer-about">
                        <div className="footer-logo">
                            <span className="logo-icon">🔬</span>
                            <span className="logo-text">E&M <span>Laboratorio</span></span>
                        </div>
                        <p>Laboratorio clínico para brindar un servicio de calidad a Médicos y pacientes, ofreciendo resultados precisos en tiempos oportunos para el adecuado diagnóstico y/o tratamiento a seguir.</p>
                        <div className="footer-libro">📖 Libro de Reclamaciones</div>
                    </div>
                    <div className="footer-links">
                        <h4>Nosotros</h4>
                        <ul>
                            <li><a href="#">Laboratorio Clínico</a></li>
                            <li><a href="#">E&M Genetics</a></li>
                            <li><a href="#">Laboratorio de Referencia</a></li>
                            <li><a href="#">Contacto</a></li>
                        </ul>
                    </div>
                    <div className="footer-contact">
                        <h4>Contacto</h4>
                        <p><span>🌐</span> www.bandl.com.br</p>
                        <p><span>✉️</span> info@bandl.com.br</p>
                        <p><span>📞</span> +55 (11) 3331-1111</p>
                    </div>
                </div>
                <div className="footer-bottom">
                    <p>&copy; {new Date().getFullYear()} E&M Laboratorio. Todos los derechos reservados.</p>
                </div>
            </footer>

            {/* Botón flotante */}
            <button className="floating-btn" onClick={() => window.location.href = '/citas'}>
                📋 Agendar Cita
            </button>
        </div>
    );
};



export default Landing;