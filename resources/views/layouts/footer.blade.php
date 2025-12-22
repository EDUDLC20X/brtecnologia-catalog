<footer>
    @php
        // Obtener información de contacto desde el CMS
        $contactPhone = \App\Models\SiteContent::get('contact.phone', '+593 98 863 3454');
        $contactAddress = \App\Models\SiteContent::get('contact.address', 'Machala, Ecuador');
        $contactHours = \App\Models\SiteContent::get('contact.hours', 'Lun - Vie: 08:00 - 18:00');
        $adminEmail = \App\Services\AdminService::getAdminEmail();
        
        // Número de WhatsApp (extraer solo dígitos del teléfono)
        $whatsappNumber = preg_replace('/[^0-9]/', '', $contactPhone);
        
        // Logo del footer desde CMS
        $footerLogo = \App\Models\SiteContent::get('global.logo_white');
        $footerLogoUrl = content_image_url($footerLogo, 'images/logo-white.png');
    @endphp
    <div class="container-xl">
        <!-- Footer Top -->
        <div class="footer-top">
            <div class="row">
                <!-- About Section -->
                <div class="col-md-3 col-lg-3">
                    <a href="{{ route('home') }}" class="d-block mb-3">
                        <img src="{{ $footerLogoUrl }}" 
                             alt="B&R Tecnología" 
                             class="footer-logo"
                             style="max-height: 55px; max-width: 180px; width: auto; height: auto; object-fit: contain;"
                             onerror="this.onerror=null; this.src='{{ asset('images/logo-br.png') }}'; this.style.filter='brightness(0) invert(1)';">
                    </a>
                    <p style="font-size: 0.9rem; color: rgba(255, 255, 255, 0.7); margin-bottom: 1rem;">
                        Su herramienta de trabajo en las mejores manos. Somos su aliado tecnológico de confianza.
                    </p>
                    <div class="footer-social">
                        <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <!-- Products Section -->
                <div class="col-md-3 col-lg-3">
                    <h6>Productos</h6>
                    <ul>
                        <li><a href="{{ route('catalog.index') }}">Todos los Productos</a></li>
                        <li><a href="{{ route('catalog.index', ['on_sale' => 1]) }}">Ofertas Especiales</a></li>
                    </ul>
                </div>

                <!-- Categories Section -->
                <div class="col-md-3 col-lg-3">
                    <h6>Categorías</h6>
                    <ul>
                        @foreach(App\Models\Category::limit(5)->get() as $category)
                            <li>
                                <a href="{{ route('catalog.index', ['categories' => [$category->id]]) }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                        <li><a href="{{ route('catalog.index') }}">Ver más →</a></li>
                    </ul>
                </div>

                <!-- Contact Section -->
                <div class="col-md-3 col-lg-3">
                    <h6>Contacto</h6>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 0.8rem;">
                            <i class="bi bi-telephone"></i> {{ $contactPhone }}
                        </li>
                        <li style="margin-bottom: 0.8rem;">
                            <i class="bi bi-envelope"></i>
                            <a href="mailto:{{ $adminEmail }}">{{ $adminEmail }}</a>
                        </li>
                        <li style="margin-bottom: 0.8rem;">
                            <i class="bi bi-geo-alt"></i> {{ $contactAddress }}
                        </li>
                        <li style="margin-bottom: 0.8rem;">
                            <i class="bi bi-clock"></i>
                            <span>{{ $contactHours }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-12 text-center">
                    <p>© {{ date('Y') }} <strong>B&R Tecnología</strong>. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- WhatsApp Button -->
<a href="https://wa.me/{{ $whatsappNumber }}?text=Hola%2C%20estoy%20interesado%20en%20sus%20productos" class="whatsapp-button" target="_blank" title="Contáctanos por WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>

<!-- Body wrapper for flexbox layout (in app.blade.php we set this) -->
<style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    
    main {
        flex: 1;
    }
</style>
