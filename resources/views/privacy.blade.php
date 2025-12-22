@extends('layouts.app')

@section('title', 'Política de Privacidad')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="mb-4">Política de Privacidad</h1>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3">1. Información que Recopilamos</h4>
                    <p>Recopilamos la siguiente información:</p>
                    <ul>
                        <li><strong>Información de cuenta:</strong> nombre, email, contraseña, dirección.</li>
                        <li><strong>Información de pago:</strong> detalles de tarjeta (procesados de forma segura).</li>
                        <li><strong>Información de navegación:</strong> cookies, dirección IP, dispositivo.</li>
                        <li><strong>Información de contacto:</strong> mensajes y consultas enviadas.</li>
                    </ul>

                    <h4 class="mb-3 mt-4">2. Cómo Usamos Tu Información</h4>
                    <p>Utilizamos la información para:</p>
                    <ul>
                        <li>Procesar pedidos y entregas.</li>
                        <li>Enviar confirmaciones y actualizaciones de pedidos.</li>
                        <li>Mejorar nuestro servicio y sitio web.</li>
                        <li>Cumplir con obligaciones legales.</li>
                        <li>Enviar promociones (si consientes).</li>
                    </ul>

                    <h4 class="mb-3 mt-4">3. Protección de Datos</h4>
                    <p>
                        Utilizamos encriptación SSL y medidas de seguridad para proteger tu información personal.
                        Los pagos se procesan a través de pasarelas seguras certificadas.
                    </p>

                    <h4 class="mb-3 mt-4">4. Cookies</h4>
                    <p>
                        Usamos cookies para mejorar la experiencia de usuario. Puedes controlar las cookies desde tu navegador.
                        Algunos servicios pueden requerir cookies para funcionar correctamente.
                    </p>

                    <h4 class="mb-3 mt-4">5. Terceros</h4>
                    <p>
                        No compartimos tu información personal con terceros, excepto cuando es necesario para:
                    </p>
                    <ul>
                        <li>Procesar pagos (procesadores de pago).</li>
                        <li>Enviar productos (transportistas).</li>
                        <li>Cumplir requisitos legales.</li>
                    </ul>

                    <h4 class="mb-3 mt-4">6. Derechos del Usuario (RGPD)</h4>
                    <p>Tienes derecho a:</p>
                    <ul>
                        <li>Acceder a tus datos personales.</li>
                        <li>Corregir datos inexactos.</li>
                        <li>Solicitar la eliminación de tus datos.</li>
                        <li>Exportar tus datos.</li>
                        <li>Retirar tu consentimiento en cualquier momento.</li>
                    </ul>

                    <h4 class="mb-3 mt-4">7. Retención de Datos</h4>
                    <p>
                        Retenemos tus datos durante el tiempo necesario para proporcionar nuestros servicios y cumplir
                        obligaciones legales. Puedes solicitar la eliminación en cualquier momento.
                    </p>

                    <h4 class="mb-3 mt-4">8. Cambios en la Política</h4>
                    <p>
                        Podemos actualizar esta política en cualquier momento. Te notificaremos de cambios significativos.
                    </p>

                    <h4 class="mb-3 mt-4">9. Contacto</h4>
                    <p>
                        Si tienes preguntas sobre tus datos o privacidad, <a href="{{ route('contact') }}">contáctanos</a>.
                    </p>
                </div>
            </div>

            <p class="text-muted mt-4 text-center">Última actualización: {{ now()->format('d/m/Y') }}</p>
        </div>
    </div>
</div>
@endsection
