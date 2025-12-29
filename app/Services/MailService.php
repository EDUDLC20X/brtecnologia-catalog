<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Mail\Mailable;

class MailService
{
    /**
     * Enviar correo con Resend API (HTTP) o fallback a SMTP
     *
     * @param string|array $to Destinatario(s)
     * @param Mailable $mailable Instancia del correo a enviar
     * @param string $context Contexto para el log (ej: 'contact', 'verification')
     * @return array ['success' => bool, 'message' => string, 'error' => string|null]
     */
    public static function send($to, Mailable $mailable, string $context = 'general'): array
    {
        $toEmail = is_array($to) ? implode(', ', $to) : $to;
        $mailableClass = get_class($mailable);
        
        Log::info("=== INICIO ENVÍO DE CORREO [{$context}] ===", [
            'to' => $toEmail,
            'mailable' => $mailableClass,
            'timestamp' => now()->toDateTimeString(),
        ]);
        
        // Intentar con Resend primero (API HTTP - no bloqueado por Render)
        $resendKey = config('services.resend.key');
        
        if (!empty($resendKey)) {
            return self::sendWithResend($to, $mailable, $context);
        }
        
        // Fallback a SMTP tradicional
        Log::info("Resend no configurado, usando SMTP tradicional");
        return self::sendWithSmtp($to, $mailable, $context);
    }
    
    /**
     * Enviar correo usando Resend API via HTTP
     */
    private static function sendWithResend($to, Mailable $mailable, string $context): array
    {
        $toEmail = is_array($to) ? $to : [$to];
        
        try {
            // Renderizar el mailable para obtener el HTML
            $mailable->build();
            
            // Obtener datos del mailable
            $fromAddress = config('mail.from.address', 'onboarding@resend.dev');
            $fromName = config('mail.from.name', 'B&R Tecnología');
            
            // Renderizar vista
            $html = $mailable->render();
            $subject = $mailable->subject ?? 'Mensaje de B&R Tecnología';
            
            Log::info("Enviando con Resend API (HTTP)", [
                'to' => $toEmail,
                'from' => "{$fromName} <{$fromAddress}>",
                'subject' => $subject,
            ]);
            
            // Llamada HTTP a Resend API
            $response = Http::withToken(config('services.resend.key'))
                ->timeout(30)
                ->post('https://api.resend.com/emails', [
                    'from' => "{$fromName} <{$fromAddress}>",
                    'to' => $toEmail,
                    'subject' => $subject,
                    'html' => $html,
                ]);
            
            if ($response->successful()) {
                $data = $response->json();
                Log::info("=== CORREO ENVIADO CON RESEND [{$context}] ===", [
                    'to' => implode(', ', $toEmail),
                    'resend_id' => $data['id'] ?? 'N/A',
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Correo enviado correctamente',
                    'error' => null,
                    'provider' => 'resend',
                    'id' => $data['id'] ?? null,
                ];
            } else {
                $errorData = $response->json();
                Log::error("=== ERROR RESEND API [{$context}] ===", [
                    'to' => implode(', ', $toEmail),
                    'status' => $response->status(),
                    'error' => $errorData,
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Error al enviar el correo',
                    'error' => $errorData['message'] ?? 'Error desconocido',
                    'provider' => 'resend',
                ];
            }
            
        } catch (\Exception $e) {
            Log::error("=== ERROR RESEND EXCEPTION [{$context}] ===", [
                'to' => implode(', ', $toEmail),
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Error al enviar el correo',
                'error' => $e->getMessage(),
                'provider' => 'resend',
            ];
        }
    }
    
    /**
     * Enviar correo usando SMTP tradicional (fallback)
     */
    private static function sendWithSmtp($to, Mailable $mailable, string $context): array
    {
        $toEmail = is_array($to) ? implode(', ', $to) : $to;
        
        try {
            Mail::to($to)->send($mailable);
            
            $failures = Mail::failures();
            if (!empty($failures)) {
                Log::warning("=== FALLO SILENCIOSO SMTP [{$context}] ===", [
                    'failures' => $failures,
                ]);
                return [
                    'success' => false,
                    'message' => 'El correo no pudo ser entregado',
                    'error' => 'Mail failures: ' . implode(', ', $failures),
                    'provider' => 'smtp',
                ];
            }
            
            Log::info("=== CORREO ENVIADO CON SMTP [{$context}] ===", [
                'to' => $toEmail,
            ]);
            
            return [
                'success' => true,
                'message' => 'Correo enviado correctamente',
                'error' => null,
                'provider' => 'smtp',
            ];
            
        } catch (\Exception $e) {
            Log::error("=== ERROR SMTP [{$context}] ===", [
                'to' => $toEmail,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Error de conexión con el servidor de correo',
                'error' => $e->getMessage(),
                'provider' => 'smtp',
            ];
        }
    }
    
    /**
     * Obtener configuración actual de mail
     */
    public static function getMailConfig(): array
    {
        return [
            'resend_configured' => !empty(config('services.resend.key')),
            'resend_key' => config('services.resend.key') ? '***SET***' : 'NOT SET',
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
        ];
    }
    
    /**
     * Verificar si el servicio de mail está configurado
     */
    public static function isConfigured(): bool
    {
        // Resend tiene prioridad
        if (!empty(config('services.resend.key'))) {
            return true;
        }
        
        // Fallback a SMTP
        return !empty(config('mail.mailers.smtp.host'))
            && !empty(config('mail.mailers.smtp.username'))
            && !empty(config('mail.mailers.smtp.password'));
    }

    /**
     * Probar conexión con el servidor de mail
     */
    public static function testConnection(): array
    {
        // Si Resend está configurado, verificar con una llamada de API simple
        if (!empty(config('services.resend.key'))) {
            try {
                $response = Http::withToken(config('services.resend.key'))
                    ->timeout(10)
                    ->get('https://api.resend.com/domains');
                
                return [
                    'success' => $response->successful(),
                    'provider' => 'resend',
                    'message' => $response->successful() 
                        ? 'Conexión con Resend API exitosa' 
                        : 'Error en Resend API',
                    'status_code' => $response->status(),
                ];
            } catch (\Exception $e) {
                return [
                    'success' => false,
                    'provider' => 'resend',
                    'message' => 'Error de conexión: ' . $e->getMessage(),
                    'status_code' => null,
                ];
            }
        }
        
        // SMTP test básico
        return [
            'success' => self::isConfigured(),
            'provider' => 'smtp',
            'message' => self::isConfigured() 
                ? 'SMTP configurado correctamente' 
                : 'SMTP no configurado',
            'status_code' => null,
        ];
    }
}
