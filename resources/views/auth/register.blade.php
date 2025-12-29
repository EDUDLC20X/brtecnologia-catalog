<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear Cuenta - B&R Tecnología</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --br-primary: #0f2744;
            --br-secondary: #1e3a5f;
            --br-accent: #3b82f6;
            --br-light: #f8fafc;
            --br-border: #e2e8f0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, var(--br-primary) 0%, var(--br-secondary) 50%, #2a5a8c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-wrapper {
            width: 100%;
            max-width: 440px;
        }

        .register-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .register-header {
            background: var(--br-primary);
            padding: 1rem 1.5rem;
            text-align: center;
        }

        .register-logo {
            max-height: 45px;
            max-width: 150px;
            width: auto;
            height: auto;
            object-fit: contain;
            margin-bottom: 0.5rem;
        }

        .register-header h1 {
            color: white;
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
        }

        .register-header p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.75rem;
            margin-top: 0.15rem;
        }

        .client-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            background: rgba(59, 130, 246, 0.2);
            color: #93c5fd;
            font-size: 0.65rem;
            font-weight: 600;
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            margin-top: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .register-body {
            padding: 1.25rem 1.5rem;
        }

        .benefits-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            margin-bottom: 1rem;
        }

        .benefits-box h4 {
            font-size: 0.7rem;
            color: #166534;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .benefits-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.15rem 0.75rem;
        }

        .benefits-list li {
            font-size: 0.7rem;
            color: #166534;
            padding: 0.1rem 0;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .benefits-list li i {
            color: #22c55e;
            font-size: 0.65rem;
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

        .form-group {
            margin-bottom: 0.75rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--br-primary);
            font-size: 0.75rem;
            margin-bottom: 0.25rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.85rem;
        }

        .form-control {
            width: 100%;
            padding: 0.55rem 0.75rem 0.55rem 2.25rem;
            border: 2px solid var(--br-border);
            border-radius: 6px;
            font-size: 0.85rem;
            color: var(--br-primary);
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--br-accent);
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
        }

        .terms-check {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .terms-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--br-accent);
            margin-top: 2px;
        }

        .terms-check label {
            font-size: 0.75rem;
            color: #64748b;
            line-height: 1.4;
        }

        .terms-check a {
            color: var(--br-accent);
            text-decoration: none;
        }

        .terms-check a:hover {
            text-decoration: underline;
        }

        .btn-register {
            width: 100%;
            padding: 0.65rem;
            background: var(--br-accent);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }

        .btn-register:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .register-footer {
            padding: 0.75rem 1.5rem;
            background: #f8fafc;
            border-top: 1px solid var(--br-border);
            text-align: center;
        }

        .register-footer p {
            font-size: 0.8rem;
            color: #64748b;
            margin: 0;
        }

        .register-footer a {
            color: var(--br-accent);
            text-decoration: none;
            font-weight: 600;
        }

        .register-footer a:hover {
            text-decoration: underline;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: white;
        }

        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-bar {
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-text {
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="register-wrapper">
        <div class="register-card">
            <div class="register-header">
                @php
                    $logoWhite = \App\Models\SiteContent::get('global.logo_white');
                    $logoUrl = $logoWhite ? content_image_url($logoWhite) : asset('images/logo-white.png');
                @endphp
                <img src="{{ $logoUrl }}" alt="B&R Tecnología" class="register-logo" onerror="this.src='{{ asset('images/logo-white.png') }}'">
                <h1>Crear mi cuenta</h1>
                <p>Únete a B&R Tecnología</p>
                <span class="client-badge"><i class="bi bi-person-check"></i> Cuenta de cliente</span>
            </div>

            <div class="register-body">
                <!-- Beneficios de registrarse -->
                <div class="benefits-box">
                    <h4><i class="bi bi-gift"></i> Beneficios de tu cuenta</h4>
                    <ul class="benefits-list">
                        <li><i class="bi bi-check-circle-fill"></i> Guarda productos en favoritos</li>
                        <li><i class="bi bi-check-circle-fill"></i> Historial de productos vistos</li>
                        <li><i class="bi bi-check-circle-fill"></i> Solicitudes más rápidas</li>
                        <li><i class="bi bi-check-circle-fill"></i> Recomendaciones personalizadas</li>
                    </ul>
                </div>

                @if($errors->any())
                    <div class="alert-box error">
                        <i class="bi bi-exclamation-circle"></i>
                        <div>
                            <strong>Por favor corrige los siguientes errores:</strong>
                            <ul style="margin: 0.5rem 0 0 1rem; padding: 0;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">Nombre completo</label>
                        <div class="input-wrapper">
                            <i class="bi bi-person"></i>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" 
                                   placeholder="Tu nombre"
                                   required 
                                   autofocus>
                        </div>
                        @error('name')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <div class="input-wrapper">
                            <i class="bi bi-envelope"></i>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" 
                                   placeholder="correo@ejemplo.com"
                                   required>
                        </div>
                        @error('email')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Teléfono <span style="font-weight: normal; color: #94a3b8;">(opcional)</span></label>
                        <div class="input-wrapper">
                            <i class="bi bi-telephone"></i>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone') }}" 
                                   placeholder="+593 999 999 999">
                        </div>
                        @error('phone')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-wrapper">
                            <i class="bi bi-lock"></i>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Mínimo 8 caracteres"
                                   required>
                        </div>
                        <div class="password-strength" id="passwordStrength" style="display: none;">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <div class="strength-text" id="strengthText"></div>
                        </div>
                        @error('password')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                        <div class="input-wrapper">
                            <i class="bi bi-lock-fill"></i>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="form-control" 
                                   placeholder="Repite tu contraseña"
                                   required>
                        </div>
                    </div>

                    <div class="terms-check">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">
                            Acepto los <a href="#" target="_blank">Términos y Condiciones</a> y la 
                            <a href="#" target="_blank">Política de Privacidad</a> de B&R Tecnología.
                        </label>
                    </div>
                    @error('terms')
                        <div class="error-text" style="margin-top: -1rem; margin-bottom: 1rem;">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="btn-register">
                        <i class="bi bi-person-plus"></i>
                        Crear mi cuenta
                    </button>
                </form>
            </div>

            <div class="register-footer">
                <p>¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
            </div>
        </div>

        <a href="{{ route('home') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Volver al inicio
        </a>
    </div>

    <script>
        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('passwordStrength');
            const fill = document.getElementById('strengthFill');
            const text = document.getElementById('strengthText');
            
            if (password.length === 0) {
                strengthDiv.style.display = 'none';
                return;
            }
            
            strengthDiv.style.display = 'block';
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            const percentage = (strength / 5) * 100;
            fill.style.width = percentage + '%';
            
            if (strength <= 2) {
                fill.style.background = '#ef4444';
                text.textContent = 'Débil';
                text.style.color = '#ef4444';
            } else if (strength <= 3) {
                fill.style.background = '#f59e0b';
                text.textContent = 'Media';
                text.style.color = '#f59e0b';
            } else {
                fill.style.background = '#22c55e';
                text.textContent = 'Fuerte';
                text.style.color = '#22c55e';
            }
        });
    </script>
</body>
</html>
