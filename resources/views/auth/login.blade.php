<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E&M Laboratorio | Iniciar Sesión</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="login-wrapper">

    {{-- LADO IZQUIERDO - Información del Laboratorio --}}
    <div class="login-hero">
        <div class="login-hero-content">
            <div class="login-hero-logo">
                <div class="login-hero-logo-icon">
                    <svg width="45" height="45" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </div>
                <div class="login-hero-logo-text">E&M<span>Laboratorio</span></div>
                <div class="login-hero-subtitle">Excelencia en Análisis Clínicos</div>
            </div>
            <h1>Bienvenido a tu<br>portal de salud</h1>
            <p>Accede a tus resultados, agenda citas y gestiona tu historial clínico desde un solo lugar.</p>
            <div class="login-stats">
                <div class="login-stat">
                    <div class="login-stat-number">+10k</div>
                    <div class="login-stat-label">Pacientes</div>
                </div>
                <div class="login-stat">
                    <div class="login-stat-number">+20</div>
                    <div class="login-stat-label">Años</div>
                </div>
                <div class="login-stat">
                    <div class="login-stat-number">100%</div>
                    <div class="login-stat-label">Confiabilidad</div>
                </div>
            </div>
        </div>
    </div>

    {{-- LADO DERECHO - Formulario de Login --}}
    <div class="login-form-side">
        <div class="login-form-container">

            <div class="login-header">
                <div class="login-header-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h3>Iniciar Sesión</h3>
                <p>Ingresa tus credenciales para continuar</p>
            </div>

            @if($errors->any())
                <div class="alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" placeholder="correo@laboratorio.com" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-arrow-right-to-bracket"></i> Ingresar
                </button>

                @if(Route::has('password.request'))
                    <div class="login-footer">
                        <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                        <p>© {{ date('Y') }} E&M Laboratorio. Todos los derechos reservados.</p>
                    </div>
                @endif
            </form>

        </div>
    </div>

</div>

</body>
</html>