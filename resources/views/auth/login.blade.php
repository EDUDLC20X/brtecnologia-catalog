<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel Administrativo - B&R Tecnología</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --admin-primary: #0f2744;
            --admin-secondary: #1e3a5f;
            --admin-accent: #3b82f6;
            --admin-light: #f8fafc;
            --admin-border: #e2e8f0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        html, body { height: 100%; }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-secondary) 50%, #2a5a8c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .login-header {
            background: var(--admin-primary);
            padding: 2rem 2rem 1.5rem;
            text-align: center;
        }

        .login-logo {
            max-height: 80px;
            max-width: 240px;
            width: auto;
            height: auto;
            object-fit: contain;
            margin-bottom: 1rem;
            /* Sin filtro - usando logo-white.png directamente */
        }

        .login-header h1 {
            color: white;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }

        .login-header p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(59, 130, 246, 0.2);
            color: #93c5fd;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            margin-top: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .login-body {
            padding: 2rem;
        }

        .alert-box {
            padding: 0.875rem 1rem;
            border-radius: 10px;
            margin-bottom: 1.25rem;
            font-size: 0.875rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .alert-box.error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-box.success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-box i {
            font-size: 1.1rem;
            flex-shrink: 0;
            margin-top: 0.1rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--admin-primary);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            border: 2px solid var(--admin-border);
            border-radius: 10px;
            font-size: 0.95rem;
            color: var(--admin-primary);
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--admin-accent);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .error-text {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 0.35rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .remember-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
        }

        .remember-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--admin-accent);
        }

        .forgot-link {
            color: var(--admin-accent);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 0.875rem 1.5rem;
            background: var(--admin-primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-login:hover {
            background: var(--admin-secondary);
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid var(--admin-border);
            margin-top: 1.5rem;
        }

        .back-link {
            color: #64748b;
            text-decoration: none;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: var(--admin-primary);
        }

        .copyright {
            text-align: center;
            margin-top: 1.5rem;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8rem;
        }

        .copyright a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }

        @media (max-width: 480px) {
            .login-wrapper {
                max-width: 100%;
            }
            
            .login-header {
                padding: 1.5rem;
            }
            
            .login-body {
                padding: 1.5rem;
            }
            
            .form-options {
                flex-direction: column;
                gap: 0.75rem;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <!-- Header -->
            @php
                $logoWhite = \App\Models\SiteContent::get('global.logo_white');
                $logoWhiteUrl = content_image_url($logoWhite, 'images/logo-white.png');
                $companyName = \App\Models\SiteContent::get('global.company_name', 'B&R Tecnología');
            @endphp
            <div class="login-header">
                <img src="{{ $logoWhiteUrl }}" 
                     alt="{{ $companyName }}" 
                     class="login-logo"
                     onerror="this.onerror=null; this.src='{{ asset('images/logo-white.png') }}';">
                <h1>{{ $companyName }}</h1>
                <p>Sistema de Gestión de Catálogo</p>
                <div class="admin-badge">
                    <i class="bi bi-shield-lock"></i>
                    Acceso Administrativo
                </div>
            </div>

            <!-- Body -->
            <div class="login-body">
                @if ($errors->any())
                    <div class="alert-box error">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <div>
                            <strong>Error de autenticación</strong>
                            @foreach ($errors->all() as $error)
                                <div style="margin-top: 4px;">{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert-box success">
                        <i class="bi bi-check-circle-fill"></i>
                        <div>{{ session('status') }}</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <div class="input-wrapper">
                            <i class="bi bi-envelope"></i>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="admin@brtecnologia.ec"
                                required 
                                autofocus>
                        </div>
                        @error('email')
                            <div class="error-text"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-wrapper">
                            <i class="bi bi-lock"></i>
                            <input 
                                id="password" 
                                type="password" 
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="••••••••"
                                required>
                        </div>
                        @error('password')
                            <div class="error-text"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-options">
                        <label class="remember-check">
                            <input type="checkbox" name="remember" id="remember">
                            <span>Mantener sesión</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Iniciar Sesión
                    </button>
                </form>

                <div class="login-footer">
                    <a href="{{ route('home') }}" class="back-link">
                        <i class="bi bi-arrow-left"></i>
                        Volver al sitio público
                    </a>
                </div>
            </div>
        </div>

        <div class="copyright">
            © {{ date('Y') }} <a href="{{ route('home') }}">B&R Tecnología</a> — Machala, Ecuador
        </div>
    </div>
</body>
</html>
