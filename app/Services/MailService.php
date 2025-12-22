<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;

class MailService
{
    /**
     * Enviar correo con logging completo y manejo de errores robusto
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
        
        // Log de configuración actual
        $config = self::getMailConfig();
        
        Log::info("=== INICIO ENVÍO DE CORREO [{$context}] ===", [
            'to' => $toEmail,
            'mailable' => $mailableClass,
            'config' => $config,
            'timestamp' => now()->toDateTimeString(),
        ]);
        
        // Verificar configuración mínima
        if (empty($config['host']) || empty($config['username']) || empty($config['password'])) {
            $errorMsg = 'Configuración de mail incompleta';
            Log::error("=== ERROR MAIL [{$context}] ===", [
                'error' => $errorMsg,
                'missing' => [
                    'host' => empty($config['host']),
                    'username' => empty($config['username']),
                    'password' => empty($config['password']),
                ],
            ]);
            return [
                'success' => false,
                'message' => 'Error de configuración del servidor de correo',
                'error' => $errorMsg,
            ];
        }
        
        try {
            // Intentar enviar
            Log::info("Ejecutando Mail::to({$toEmail})->send()...");
            
            Mail::to($to)->send($mailable);
            
            // Verificar si hubo fallos silenciosos
            $failures = Mail::failures();
            if (!empty($failures)) {
                Log::warning("=== FALLO SILENCIOSO MAIL [{$context}] ===", [
                    'failures' => $failures,
                ]);
                return [
                    'success' => false,
                    'message' => 'El correo no pudo ser entregado',
                    'error' => 'Mail failures: ' . implode(', ', $failures),
                ];
            }
            
            Log::info("=== CORREO ENVIADO EXITOSAMENTE [{$context}] ===", [
                'to' => $toEmail,
                'mailable' => $mailableClass,
            ]);
            
            return [
                'success' => true,
                'message' => 'Correo enviado correctamente',
                'error' => null,
            ];
            
        } catch (\Swift_TransportException $e) {
            // Error de transporte SMTP
            Log::error("=== ERROR SMTP [{$context}] ===", [
                'to' => $toEmail,
                'exception' => 'Swift_TransportException',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
            return [
                'success' => false,
                'message' => 'Error de conexión con el servidor de correo',
                'error' => $e->getMessage(),
            ];
            
        } catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
            // Error de transporte Symfony (Laravel 9+)
            Log::error("=== ERROR TRANSPORT [{$context}] ===", [
                'to' => $toEmail,
                'exception' => 'Symfony TransportException',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'debug' => $e->getDebug() ?? 'N/A',
            ]);
            return [
                'success' => false,
                'message' => 'Error de conexión con el servidor de correo',
                'error' => $e->getMessage(),
            ];
            
        } catch (\Exception $e) {
            // Error general
            Log::error("=== ERROR GENERAL MAIL [{$context}] ===", [
                'to' => $toEmail,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return [
                'success' => false,
                'message' => 'Error al enviar el correo',
                'error' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Obtener configuración actual de mail (sanitizada)
     */
    public static function getMailConfig(): array
    {
        return [
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'encryption' => config('mail.mailers.smtp.encryption'),
            'username' => config('mail.mailers.smtp.username'),
            'password' => config('mail.mailers.smtp.password') ? '***SET***' : 'NOT SET',
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
            'timeout' => config('mail.mailers.smtp.timeout'),
        ];
    }
    
    /**
     * Verificar si la configuración de mail está completa
     */
    public static function isConfigured(): bool
    {
        return !empty(config('mail.mailers.smtp.host'))
            && !empty(config('mail.mailers.smtp.username'))
            && !empty(config('mail.mailers.smtp.password'));
    }
    
    /**
     * Probar conexión SMTP sin enviar correo
     */
    public static function testConnection(): array
    {
        $config = self::getMailConfig();
        
        Log::info("=== TEST CONEXIÓN SMTP ===", $config);
        
        if (!self::isConfigured()) {
            return [
                'success' => false,
                'message' => 'Configuración incompleta',
                'config' => $config,
            ];
        }
        
        try {
            // Intentar crear el transporte
            $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
                config('mail.mailers.smtp.host'),
                config('mail.mailers.smtp.port'),
                config('mail.mailers.smtp.encryption') === 'tls'
            );
            
            $transport->setUsername(config('mail.mailers.smtp.username'));
            $transport->setPassword(config('mail.mailers.smtp.password'));
            
            // Intentar conectar
            $transport->start();
            $transport->stop();
            
            Log::info("=== TEST SMTP EXITOSO ===");
            
            return [
                'success' => true,
                'message' => 'Conexión SMTP exitosa',
                'config' => $config,
            ];
            
        } catch (\Exception $e) {
            Log::error("=== TEST SMTP FALLIDO ===", [
                'error' => $e->getMessage(),
                'config' => $config,
            ]);
            
            return [
                'success' => false,
                'message' => 'Error de conexión: ' . $e->getMessage(),
                'config' => $config,
            ];
        }
    }
}
