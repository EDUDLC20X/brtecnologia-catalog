<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verificar Email - B&R Tecnología</title>
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

        .verify-wrapper {
            width: 100%;
            max-width: 440px;
        }

        .verify-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .verify-header {
            background: var(--admin-primary);
            padding: 2rem;
            text-align: center;
        }

        .verify-icon {
            width: 80px;
            height: 80px;
            background: transparent;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .verify-icon img {
            max-height: 70px;
            max-width: 180px;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .verify-header h1 {
            color: white;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0 0 0.25rem;
        }

        .verify-header p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin: 0;
        }

        .verify-body {
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

        .btn-verify {
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

        .btn-verify:hover {
            background: var(--admin-secondary);
            transform: translateY(-1px);
        }

        .btn-logout {
            width: 100%;
            padding: 0.75rem 1.25rem;
            background: transparent;
            color: #64748b;
            border: 2px solid var(--admin-border);
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            margin-top: 0.75rem;
        }

        .btn-logout:hover {
            background: #f8fafc;
            color: #ef4444;
            border-color: #fecaca;
        }

        .copyright {
            text-align: center;
            margin-top: 1.5rem;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8rem;
        }

        @media (max-width: 480px) {
            .verify-wrapper { max-width: 100%; }
            .verify-header, .verify-body { padding: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="verify-wrapper">
        <div class="verify-card">
            <div class="verify-header">
                <div class="verify-icon">
                    <img src="{{ asset('images/logo-white.png') }}" alt="B&R Tecnología" onerror="this.parentElement.innerHTML='<i class=\'bi bi-envelope-check\' style=\'font-size:1.5rem;color:#93c5fd;\'></i>';">
                </div>
                <h1>Verificar Email</h1>
                <p>Panel Administrativo - B&R Tecnología</p>
            </div>

            <div class="verify-body">
                <div class="info-box">
                    <i class="bi bi-info-circle"></i>
                    <div>
                        Gracias por registrarte. Antes de continuar, por favor verifica tu correo electrónico haciendo clic en el enlace que te enviamos. Si no recibiste el correo, te enviaremos otro.
                    </div>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="alert-box success">
                        <i class="bi bi-check-circle-fill"></i>
                        <div>
                            Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn-verify">
                        <i class="bi bi-envelope"></i>
                        Reenviar Email de Verificación
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="bi bi-box-arrow-right"></i>
                        Cerrar Sesión
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
