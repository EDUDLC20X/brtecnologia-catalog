@extends('layouts.app')

@section('title', 'Preguntas Frecuentes')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="mb-4">Preguntas Frecuentes (FAQ)</h1>

            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                            ¿Cuál es el tiempo de envío?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            El tiempo de envío típico es de 3-5 días hábiles para el envío estándar y 1-2 días para envío express.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                            ¿Cuáles son los costos de envío?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Los costos de envío dependen de tu ubicación. Ofrecemos envío gratis para pedidos mayores a $50.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                            ¿Puedo devolver un producto?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Sí, aceptamos devoluciones dentro de 30 días de la compra. El producto debe estar en condiciones originales.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                            ¿Qué métodos de pago aceptan?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Aceptamos tarjetas de crédito (Visa, Mastercard), PayPal y transferencia bancaria.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFive">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive">
                            ¿Cómo rastreo mi pedido?
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Una vez que tu pedido sea enviado, recibirás un email con el número de seguimiento para rastrear tu envío.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSix">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix">
                            ¿Ofrecen garantía en los productos?
                        </button>
                    </h2>
                    <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Sí, todos nuestros productos incluyen garantía del fabricante. Los detalles específicos varían por producto.
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info mt-4">
                <strong>¿No encontraste tu respuesta?</strong> <a href="{{ route('contact') }}" class="alert-link">Contáctanos</a>
            </div>
        </div>
    </div>
</div>
@endsection
