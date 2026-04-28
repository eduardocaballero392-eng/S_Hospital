<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital - Iniciar Sesión</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="login-body">

    <div class="login-wrapper">

        {{-- LADO IZQUIERDO - Imagen --}}
        <div class="login-image">
            <div class="login-image-overlay">
                <div class="login-image-content">
                    <h1 class="text-white fw-bold">Sistema Hospital </h1>
                    <p class="text-white opacity-75">Cuidando tu salud con tecnología de vanguardia</p>
                </div>
            </div>
        </div>

        {{-- LADO DERECHO - Formulario --}}
        <div class="login-form-side">
            <div class="login-form-container">

               {{-- Logo --}}
<div class="text-center mb-4">
    <div class="logo-icon mx-auto mb-3">
        <span style="color:white; font-size: 2rem;">✚</span>
    </div>
    <h3 class="fw-bold text-primary">Bienvenido</h3>
    <p class="text-muted small">Inicia sesión para continuar</p>
</div>

                {{-- Errores --}}
                @if($errors->any())
                    <div class="alert alert-danger rounded-3">
                         {{ $errors->first() }}
                    </div>
                @endif

                {{-- Formulario --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Correo Electrónico</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control form-control-lg rounded-3"
                            placeholder="correo@hospital.com"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Contraseña</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control form-control-lg rounded-3"
                            placeholder="••••••••"
                            required
                        >
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-bold">
                             Iniciar Sesión
                        </button>
                    </div>

                    @if(Route::has('password.request'))
                        <div class="text-center mt-3">
                            <a href="{{ route('password.request') }}" class="text-primary small">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                    @endif

                </form>

                <p class="text-center text-muted small mt-4">
                    © {{ date('Y') }} Hospital System.
                </p>

            </div>
        </div>

    </div>

</body>
</html>