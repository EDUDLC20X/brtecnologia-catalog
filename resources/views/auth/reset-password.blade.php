<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Restablecer Contraseña - B&R Tecnología</title>
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

        .reset-wrapper {
            width: 100%;
            max-width: 440px;
        }

        .reset-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .reset-header {
            background: var(--admin-primary);
            padding: 2rem;
            text-align: center;
        }

        .reset-icon {
            width: 80px;
            height: 80px;
            background: transparent;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .reset-icon img {
            max-height: 70px;
            max-width: 180px;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .reset-header h1 {
            color: white;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0 0 0.25rem;
        }

        .reset-header p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin: 0;
        }

        .reset-body {
            padding: 2rem;
        }

        .info-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            color: #0369a1;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .info-box i {
            font-size: 1.1rem;
            flex-shrink: 0;
            margin-top: 0.1rem;
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

        .alert-box.success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-box.error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
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

        .password-requirements {
            background: #f8fafc;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
            font-size: 0.8rem;
            color: #64748b;
        }

        .password-requirements ul {
            margin: 0.5rem 0 0 1rem;
            padding: 0;
        }

        .password-requirements li {
            margin-bottom: 0.25rem;
        }

        .btn-reset {
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
            margin-top: 1.5rem;
        }

        .btn-reset:hover {
            background: var(--admin-secondary);
            transform: translateY(-1px);
        }

        .reset-footer {
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

        @media (max-width: 480px) {
            .reset-wrapper { max-width: 100%; }
            .reset-header, .reset-body { padding: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="reset-wrapper">
        <div class="reset-card">
            <div class="reset-header">
                <div class="reset-icon">
                    <img src="{{ asset('images/logo-white.png') }}" alt="B&R Tecnología" onerror="this.parentElement.innerHTML='<i class=\'bi bi-shield-lock\' style=\'font-size:1.5rem;color:#93c5fd;\'></i>';">
                </div>
                <h1>Nueva Contraseña</h1>
                <p>Panel Administrativo - B&R Tecnología</p>
            </div>

            <div class="reset-body">
                <div class="info-box">
                    <i class="bi bi-info-circle"></i>
                    <div>
                        Ingresa tu nueva contraseña de administrador. 
                        Asegúrate de elegir una contraseña segura.
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert-box error">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="form-group">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <div class="input-wrapper">
                            <i class="bi bi-envelope"></i>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email', $request->email) }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required 
                                readonly
                                style="background-color: #f8fafc;">
                        </div>
                        @error('email')
                            <div class="error-text"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <div class="input-wrapper">
                            <i class="bi bi-lock"></i>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Ingresa tu nueva contraseña"
                                required 
                                autofocus>
                        </div>
                        @error('password')
                            <div class="error-text"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                        <div class="input-wrapper">
                            <i class="bi bi-lock-fill"></i>
                            <input 
                                id="password_confirmation" 
                                type="password" 
                                name="password_confirmation" 
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                placeholder="Repite tu nueva contraseña"
                                required>
                        </div>
                        @error('password_confirmation')
                            <div class="error-text"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="password-requirements">
                        <strong>Requisitos de seguridad:</strong>
                        <ul>
                            <li>Mínimo 8 caracteres</li>
                            <li>Se recomienda usar letras, números y símbolos</li>
                        </ul>
                    </div>

                    <button type="submit" class="btn-reset">
                        <i class="bi bi-check-lg"></i>
                        Restablecer Contraseña
                    </button>
                </form>

                <div class="reset-footer">
                    <a href="{{ route('login') }}" class="back-link">
                        <i class="bi bi-arrow-left"></i>
                        Volver al inicio de sesión
                    </a>
                </div>
            </div>
        </div>

        <div class="copyright">
            © {{ date('Y') }} B&R Tecnología — Machala, Ecuador
        </div>
    </div>
</body>
</html>
