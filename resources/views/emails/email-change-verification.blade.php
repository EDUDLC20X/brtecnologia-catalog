<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirma tu nuevo correo</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #0f2744;
        }
        h1 {
            color: #0f2744;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .info-box {
            background-color: #e8f4fd;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box strong {
            color: #1e3a5f;
        }
        .btn {
            display: inline-block;
            background-color: #0f2744;
            color: #ffffff !important;
            padding: 14px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
        }
        .btn:hover {
            background-color: #1e3a5f;
        }
        .warning {
            background-color: #fef3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 14px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 13px;
            color: #666;
            text-align: center;
        }
        .url-fallback {
            word-break: break-all;
            font-size: 12px;
            color: #666;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">{{ config('app.name') }}</div>
        </div>

        <h1>Confirma tu nuevo correo electrónico</h1>

        <p>Hola <strong>{{ $userName }}</strong>,</p>

        <p>Has solicitado cambiar tu correo electrónico en {{ config('app.name') }}.</p>

        <div class="info-box">
            <strong>Nuevo correo electrónico:</strong><br>
            {{ $newEmail }}
        </div>

        <p>Para confirmar este cambio, haz clic en el siguiente botón:</p>

        <div style="text-align: center;">
            <a href="{{ $verificationUrl }}" class="btn">Confirmar nuevo correo</a>
        </div>

        <p class="url-fallback">
            Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
            {{ $verificationUrl }}
        </p>

        <div class="warning">
            <strong>⚠️ Importante:</strong><br>
            • Este enlace expira en <strong>60 minutos</strong>.<br>
            • Si no solicitaste este cambio, ignora este correo.<br>
            • Tu correo actual permanecerá sin cambios hasta que confirmes.
        </div>

        <div class="footer">
            <p>Este correo fue enviado automáticamente por {{ config('app.name') }}.</p>
            <p>Por favor, no respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html>
