<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Mensaje de Contacto</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f8;">
    <div style="max-width: 600px; margin: 0 auto; padding: 24px;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #0f2744 0%, #1e3a5f 100%); border-radius: 12px 12px 0 0; padding: 24px 32px; text-align: center;">
            <h1 style="margin: 0; color: #ffffff; font-size: 20px; font-weight: 600;">
                ✉️ Nuevo Mensaje de Contacto
            </h1>
        </div>
        
        <!-- Content -->
        <div style="background: #ffffff; padding: 32px; border-left: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0;">
            <p style="margin: 0 0 20px; color: #475569; font-size: 14px;">
                Has recibido un nuevo mensaje a través del formulario de contacto del sitio web.
            </p>
            
            <!-- Data Table -->
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 24px;">
                <tr>
                    <td style="padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; font-weight: 600; color: #0f2744; width: 35%;">Nombre</td>
                    <td style="padding: 12px 16px; border: 1px solid #e2e8f0; color: #334155;">{{ $data['name'] }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; font-weight: 600; color: #0f2744;">Email</td>
                    <td style="padding: 12px 16px; border: 1px solid #e2e8f0; color: #334155;">
                        <a href="mailto:{{ $data['email'] }}" style="color: #3b82f6; text-decoration: none;">{{ $data['email'] }}</a>
                    </td>
                </tr>
                @if(!empty($data['subject']))
                <tr>
                    <td style="padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; font-weight: 600; color: #0f2744;">Asunto</td>
                    <td style="padding: 12px 16px; border: 1px solid #e2e8f0; color: #334155;">{{ $data['subject'] }}</td>
                </tr>
                @endif
            </table>

            <!-- Message Box -->
            <div style="margin-bottom: 24px;">
                <div style="font-weight: 600; color: #0f2744; margin-bottom: 8px; font-size: 14px;">Mensaje:</div>
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; color: #475569; line-height: 1.6; font-size: 14px;">
                    {!! nl2br(e($data['message'])) !!}
                </div>
            </div>

            <!-- Reply Button -->
            <div style="text-align: center;">
                <a href="mailto:{{ $data['email'] }}?subject=Re: {{ $data['subject'] ?? 'Contacto B&R Tecnología' }}" style="display: inline-block; background: #3b82f6; color: #ffffff; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;">
                    Responder al Cliente
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 12px 12px; padding: 20px 32px; text-align: center;">
            <p style="margin: 0 0 8px; color: #64748b; font-size: 12px;">
                B&R Tecnología — Panel de Administración
            </p>
            <p style="margin: 0; color: #94a3b8; font-size: 11px;">
                Recibido: {{ $data['sent_at'] ?? now()->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>
</body>
</html>
