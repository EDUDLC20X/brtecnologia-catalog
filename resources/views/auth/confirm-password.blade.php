<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Confirmar Contraseña - B&R Tecnología</title>
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

        .confirm-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .confirm-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .confirm-header {
            background: var(--admin-primary);
            padding: 2rem;
            text-align: center;
        }

        .confirm-icon {
            width: 80px;
            height: 80px;
            background: transparent;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .confirm-icon img {
            max-height: 70px;
            max-width: 180px;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .confirm-header h1 {
            color: white;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0 0 0.25rem;
        }

        .confirm-header p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin: 0;
        }

        .confirm-body {
            padding: 2rem;
        }

        .info-box {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            color: #92400e;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .info-box i {
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

        .btn-confirm {
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

        .btn-confirm:hover {
            background: var(--admin-secondary);
            transform: translateY(-1px);
        }

        .copyright {
            text-align: center;
            margin-top: 1.5rem;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8rem;
        }

        @media (max-width: 480px) {
            .confirm-wrapper { max-width: 100%; }
            .confirm-header, .confirm-body { padding: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="confirm-wrapper">
        <div class="confirm-card">
            <div class="confirm-header">
                <div class="confirm-icon">
                    <img src="{{ asset('images/logo-white.png') }}" alt="B&R Tecnología" onerror="this.parentElement.innerHTML='<i class=\'bi bi-shield-check\' style=\'font-size:1.5rem;color:#93c5fd;\'></i>';">
                </div>
                <h1>Confirmar Contraseña</h1>
                <p>Panel Administrativo - B&R Tecnología</p>
            </div>

            <div class="confirm-body">
                <div class="info-box">
                    <i class="bi bi-exclamation-triangle"></i>
                    <div>
                        Esta es una zona segura del sistema. Por favor confirma tu contraseña de administrador para continuar.
                    </div>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña Actual</label>
                        <div class="input-wrapper">
                            <i class="bi bi-lock"></i>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Ingresa tu contraseña"
                                required 
                                autocomplete="current-password"
                                autofocus>
                        </div>
                        @error('password')
                            <div class="error-text"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-confirm">
                        <i class="bi bi-check-lg"></i>
                        Confirmar
                    </button>
                </form>
            </div>
        </div>

        <div class="copyright">
            © {{ date('Y') }} B&R Tecnología — Machala, Ecuador
        </div>
    </div>
</body>
</html>
