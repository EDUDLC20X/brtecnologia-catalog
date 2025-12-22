@extends('layouts.app')

@section('title', 'Términos y Condiciones')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="mb-4">Términos y Condiciones</h1>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3">1. Aceptación de Términos</h4>
                    <p>
                        Al acceder y utilizar este sitio web, aceptas estar vinculado por estos términos y condiciones.
                        Si no estás de acuerdo con alguna parte de estos términos, no uses este sitio.
                    </p>

                    <h4 class="mb-3 mt-4">2. Licencia de Uso</h4>
                    <p>
                        Te otorgamos una licencia limitada, no exclusiva y no transferible para acceder y usar este sitio
                        únicamente para propósitos personales y no comerciales.
                    </p>

                    <h4 class="mb-3 mt-4">3. Restricciones de Uso</h4>
                    <p>No puedes:</p>
                    <ul>
                        <li>Reproducir o distribuir contenido del sitio sin autorización.</li>
                        <li>Usar el sitio para actividades ilegales o no autorizadas.</li>
                        <li>Interferir con el funcionamiento normal del sitio.</li>
                        <li>Realizar scraping o minería de datos.</li>
                    </ul>

                    <h4 class="mb-3 mt-4">4. Productos y Precios</h4>
                    <p>
                        Nos reservamos el derecho de cambiar precios, descripción de productos y disponibilidad en cualquier momento.
                        No garantizamos la disponibilidad de productos hasta que se haya completado tu pedido.
                    </p>

                    <h4 class="mb-3 mt-4">5. Pedidos y Pagos</h4>
                    <p>
                        Todos los pedidos están sujetos a aceptación. Nos reservamos el derecho de rechazar o cancelar cualquier pedido.
                        El pago debe ser recibido antes del envío, excepto en casos acordados previamente.
                    </p>

                    <h4 class="mb-3 mt-4">6. Envío y Entrega</h4>
                    <p>
                        No nos hacemos responsables por daños causados durante el envío. El riesgo pasa al cliente una vez que
                        el transportista recibe el paquete.
                    </p>

                    <h4 class="mb-3 mt-4">7. Limitación de Responsabilidad</h4>
                    <p>
                        Este sitio se proporciona "tal cual" sin garantías. No somos responsables por daños indirectos, incidentales o consecuentes.
                    </p>

                    <h4 class="mb-3 mt-4">8. Cambios en los Términos</h4>
                    <p>
                        Nos reservamos el derecho de modificar estos términos en cualquier momento. El uso continuado del sitio
                        constituye aceptación de los términos modificados.
                    </p>

                    <h4 class="mb-3 mt-4">9. Ley Aplicable</h4>
                    <p>
                        Estos términos se regirán por las leyes del país donde operamos, excluyendo conflictos de leyes.
                    </p>

                    <h4 class="mb-3 mt-4">10. Contacto</h4>
                    <p>
                        Si tienes preguntas sobre estos términos, <a href="{{ route('contact') }}">contáctanos</a>.
                    </p>
                </div>
            </div>

            <p class="text-muted mt-4 text-center">Última actualización: {{ now()->format('d/m/Y') }}</p>
        </div>
    </div>
</div>
@endsection
