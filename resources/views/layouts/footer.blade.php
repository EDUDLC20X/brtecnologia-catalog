<footer class="footer-modern">
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
        
        // Redes sociales desde CMS
        $socialFacebook = \App\Models\SiteContent::get('contact.social_facebook', 'https://facebook.com');
        $socialInstagram = \App\Models\SiteContent::get('contact.social_instagram', 'https://instagram.com');
        $socialTwitter = \App\Models\SiteContent::get('contact.social_twitter', 'https://twitter.com');
    @endphp
    
    <div class="footer-wave">
        <svg viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 50L48 45.7C96 41.3 192 32.7 288 35.8C384 39 480 54 576 59.2C672 64.3 768 59.7 864 50C960 40.3 1056 25.7 1152 22.5C1248 19.3 1344 27.7 1392 31.8L1440 36V100H1392C1344 100 1248 100 1152 100C1056 100 960 100 864 100C768 100 672 100 576 100C480 100 384 100 288 100C192 100 96 100 48 100H0V50Z" fill="currentColor"/>
        </svg>
    </div>
    
    <div class="container-xl">
        <!-- Footer Top -->
        <div class="footer-top">
            <div class="row g-5">
                <!-- About Section -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand">
                        <a href="{{ route('home') }}" class="d-inline-block mb-4">
                            <img src="{{ asset('images/logo-white.png') }}" 
                                 alt="B&R Tecnología" 
                                 class="footer-logo">
                        </a>
                        <p class="footer-desc">
                            Su equipo de tecnología en las mejores manos. Somos su aliado tecnológico de confianza, ofreciendo los mejores productos y soluciones.
                        </p>
                        <div class="footer-social">
                            @if($socialFacebook)
                                <a href="{{ $socialFacebook }}" class="social-btn" target="_blank" rel="noopener" title="Facebook">
                                    <i class="bi bi-facebook"></i>
                                </a>
                            @endif
                            @if($socialInstagram)
                                <a href="{{ $socialInstagram }}" class="social-btn" target="_blank" rel="noopener" title="Instagram">
                                    <i class="bi bi-instagram"></i>
                                </a>
                            @endif
                            @if($socialTwitter)
                                <a href="{{ $socialTwitter }}" class="social-btn" target="_blank" rel="noopener" title="Twitter">
                                    <i class="bi bi-twitter"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="footer-title">Productos</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('catalog.index') }}"><i class="bi bi-chevron-right"></i>Todos los Productos</a></li>
                        <li><a href="{{ route('catalog.index', ['on_sale' => 1]) }}"><i class="bi bi-chevron-right"></i>Ofertas Especiales</a></li>
                    </ul>
                </div>

                <!-- Categories Section -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="footer-title">Categorías</h6>
                    <ul class="footer-links">
                        @foreach(App\Models\Category::limit(4)->get() as $category)
                            <li>
                                <a href="{{ route('catalog.index', ['categories' => [$category->id]]) }}">
                                    <i class="bi bi-chevron-right"></i>{{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                        <li><a href="{{ route('catalog.index') }}"><i class="bi bi-chevron-right"></i>Ver más</a></li>
                    </ul>
                </div>

                <!-- Contact Section -->
                <div class="col-lg-4 col-md-6">
                    <h6 class="footer-title">Contacto</h6>
                    <ul class="footer-contact">
                        <li>
                            <div class="contact-icon">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div class="contact-info">
                                <span class="contact-label">Teléfono</span>
                                <a href="tel:{{ $contactPhone }}">{{ $contactPhone }}</a>
                            </div>
                        </li>
                        <li>
                            <div class="contact-icon">
                                <i class="bi bi-envelope-fill"></i>
                            </div>
                            <div class="contact-info">
                                <span class="contact-label">Email</span>
                                <a href="mailto:{{ $adminEmail }}">{{ $adminEmail }}</a>
                            </div>
                        </li>
                        <li>
                            <div class="contact-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="contact-info">
                                <span class="contact-label">Ubicación</span>
                                <span>{{ $contactAddress }}</span>
                            </div>
                        </li>
                        <li>
                            <div class="contact-icon">
                                <i class="bi bi-clock-fill"></i>
                            </div>
                            <div class="contact-info">
                                <span class="contact-label">Horario</span>
                                <span>{{ $contactHours }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">© {{ date('Y') }} <strong>B&R Tecnología</strong>. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-badges">
                        <span class="badge-item"><i class="bi bi-shield-check"></i> Sitio Seguro</span>
                        <span class="badge-item"><i class="bi bi-credit-card"></i> Pagos Seguros</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- WhatsApp Button Mejorado -->
<a href="https://wa.me/{{ $whatsappNumber }}?text=Hola%2C%20estoy%20interesado%20en%20sus%20productos" class="whatsapp-float" target="_blank" title="Contáctanos por WhatsApp">
    <div class="whatsapp-pulse"></div>
    <i class="bi bi-whatsapp"></i>
</a>

<style>
/* ============================================
   FOOTER MODERNO - B&R Tecnología 2025
   ============================================ */

.footer-modern {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
    color: rgba(255, 255, 255, 0.85);
    position: relative;
    padding-top: 0;
}

.footer-wave {
    color: #0f172a;
    margin-bottom: -1px;
    line-height: 0;
}

.footer-wave svg {
    width: 100%;
    height: auto;
}

.footer-top {
    padding: 4rem 0 3rem;
}

/* Brand Section */
.footer-brand {
    max-width: 320px;
}

.footer-logo {
    height: 55px;
    width: auto;
    max-width: 180px;
    object-fit: contain;
}

.footer-desc {
    font-size: 0.95rem;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 1.5rem;
}

/* Social Buttons */
.footer-social {
    display: flex;
    gap: 0.75rem;
}

.social-btn {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    color: #ffffff;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.social-btn:hover {
    background: linear-gradient(135deg, #3b82f6, #0ea5e9);
    border-color: transparent;
    transform: translateY(-3px);
    color: #ffffff;
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
}

/* Footer Titles */
.footer-title {
    color: #ffffff;
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    position: relative;
    padding-bottom: 0.75rem;
}

.footer-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 3px;
    background: linear-gradient(135deg, #3b82f6, #0ea5e9);
    border-radius: 10px;
}

/* Footer Links */
.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 0.75rem;
}

.footer-links a {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    font-size: 0.95rem;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
}

.footer-links a i {
    font-size: 0.7rem;
    margin-right: 0.5rem;
    transition: transform 0.3s ease;
}

.footer-links a:hover {
    color: #ffffff;
    transform: translateX(5px);
}

.footer-links a:hover i {
    color: #3b82f6;
}

/* Footer Contact */
.footer-contact {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-contact li {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.25rem;
}

.contact-icon {
    width: 44px;
    height: 44px;
    min-width: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    color: #60a5fa;
    font-size: 1.1rem;
}

.contact-info {
    display: flex;
    flex-direction: column;
}

.contact-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: rgba(255, 255, 255, 0.5);
    margin-bottom: 0.25rem;
}

.contact-info a,
.contact-info span {
    color: rgba(255, 255, 255, 0.85);
    text-decoration: none;
    font-size: 0.95rem;
}

.contact-info a:hover {
    color: #ffffff;
}

/* Footer Bottom */
.footer-bottom {
    padding: 1.5rem 0;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.footer-bottom p {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.9rem;
}

.footer-bottom strong {
    color: #ffffff;
}

.footer-badges {
    display: flex;
    gap: 1.5rem;
    justify-content: flex-end;
}

.badge-item {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.6);
}

.badge-item i {
    color: #10b981;
}

/* WhatsApp Button Mejorado */
.whatsapp-float {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.75rem;
    box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
    transition: all 0.3s ease;
    z-index: 1000;
}

.whatsapp-float:hover {
    transform: scale(1.1) translateY(-5px);
    box-shadow: 0 8px 30px rgba(37, 211, 102, 0.5);
    color: white;
}

.whatsapp-pulse {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: inherit;
    animation: whatsapp-pulse-animation 2s infinite;
    z-index: -1;
}

@keyframes whatsapp-pulse-animation {
    0% {
        transform: scale(1);
        opacity: 0.7;
    }
    100% {
        transform: scale(1.5);
        opacity: 0;
    }
}

/* Responsive */
@media (max-width: 991.98px) {
    .footer-top {
        padding: 3rem 0 2rem;
    }
    
    .footer-brand {
        max-width: 100%;
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .footer-social {
        justify-content: center;
    }
    
    .footer-title::after {
        left: 50%;
        transform: translateX(-50%);
    }
}

@media (max-width: 767.98px) {
    .footer-title {
        text-align: center;
    }
    
    .footer-links {
        text-align: center;
    }
    
    .footer-links a {
        justify-content: center;
    }
    
    .footer-contact li {
        justify-content: center;
    }
    
    .footer-badges {
        justify-content: center;
        margin-top: 1rem;
    }
    
    .whatsapp-float {
        bottom: 20px;
        right: 20px;
        width: 54px;
        height: 54px;
        font-size: 1.5rem;
    }
}
</style>
