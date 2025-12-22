<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteContent;

class SiteContentSeeder extends Seeder
{
    /**
     * Crear contenido editable del sitio
     */
    public function run(): void
    {
        $contents = [
            // ===============================
            // CONFIGURACIÓN GLOBAL (7 items)
            // ===============================
            [
                'section' => 'global',
                'key' => 'global.company_name',
                'label' => 'Nombre de la Empresa',
                'type' => 'text',
                'value' => 'B&R Tecnología',
                'default_value' => 'B&R Tecnología',
                'help_text' => 'Nombre que aparece en el sitio',
                'order' => 1,
            ],
            [
                'section' => 'global',
                'key' => 'global.slogan',
                'label' => 'Slogan',
                'type' => 'text',
                'value' => 'Su herramienta de trabajo en las mejores manos',
                'default_value' => 'Su herramienta de trabajo en las mejores manos',
                'help_text' => 'Frase que acompaña al logo',
                'order' => 2,
            ],
            [
                'section' => 'global',
                'key' => 'global.logo',
                'label' => 'Logo Principal',
                'type' => 'image',
                'value' => null,
                'default_value' => 'images/logo.png',
                'help_text' => 'Logo para la página de inicio',
                'order' => 3,
            ],
            [
                'section' => 'global',
                'key' => 'global.navbar_logo',
                'label' => 'Logo del Navbar',
                'type' => 'image',
                'value' => null,
                'default_value' => 'images/logo.png',
                'help_text' => 'Logo para la barra de navegación',
                'order' => 4,
            ],
            [
                'section' => 'global',
                'key' => 'global.logo_white',
                'label' => 'Logo Blanco',
                'type' => 'image',
                'value' => null,
                'default_value' => 'images/logo-white.png',
                'help_text' => 'Logo para footer y fondos oscuros',
                'order' => 5,
            ],
            [
                'section' => 'global',
                'key' => 'global.footer_text',
                'label' => 'Texto del Footer',
                'type' => 'textarea',
                'value' => 'Su herramienta de trabajo en las mejores manos.',
                'default_value' => 'Su herramienta de trabajo en las mejores manos.',
                'help_text' => 'Texto descriptivo en el footer',
                'order' => 6,
            ],
            [
                'section' => 'global',
                'key' => 'global.copyright',
                'label' => 'Copyright',
                'type' => 'text',
                'value' => '© 2025 B&R Tecnología. Todos los derechos reservados.',
                'default_value' => '© 2025 B&R Tecnología',
                'help_text' => 'Texto de copyright',
                'order' => 7,
            ],

            // ===============================
            // PÁGINA DE INICIO (8 items)
            // ===============================
            [
                'section' => 'home',
                'key' => 'home.hero.title',
                'label' => 'Título del Hero',
                'type' => 'text',
                'value' => 'Herramientas eléctricas, equipos industriales y tecnología',
                'default_value' => 'Herramientas eléctricas, equipos industriales y tecnología',
                'help_text' => 'Título grande del banner principal',
                'order' => 1,
            ],
            [
                'section' => 'home',
                'key' => 'home.hero.subtitle',
                'label' => 'Subtítulo del Hero',
                'type' => 'textarea',
                'value' => 'Su herramienta de trabajo en las mejores manos',
                'default_value' => 'Su herramienta de trabajo en las mejores manos',
                'help_text' => 'Texto debajo del título principal',
                'order' => 2,
            ],
            [
                'section' => 'home',
                'key' => 'home.hero.image',
                'label' => 'Imagen del Hero',
                'type' => 'image',
                'value' => null,
                'default_value' => 'images/hero-product.png',
                'help_text' => 'Imagen del banner principal',
                'order' => 3,
            ],
            [
                'section' => 'home',
                'key' => 'home.hero.search_placeholder',
                'label' => 'Placeholder del Buscador',
                'type' => 'text',
                'value' => 'Buscar taladro, multímetro, robot, ...',
                'default_value' => 'Buscar taladro, multímetro, robot, ...',
                'help_text' => 'Texto del campo de búsqueda',
                'order' => 4,
            ],
            [
                'section' => 'home',
                'key' => 'home.categories.title',
                'label' => 'Título Sección Categorías',
                'type' => 'text',
                'value' => 'Nuestras Categorías',
                'default_value' => 'Nuestras Categorías',
                'help_text' => 'Título de la sección de categorías',
                'order' => 5,
            ],
            [
                'section' => 'home',
                'key' => 'home.categories.subtitle',
                'label' => 'Subtítulo Categorías',
                'type' => 'text',
                'value' => 'Encuentra todo lo que necesitas para tu negocio',
                'default_value' => 'Encuentra todo lo que necesitas',
                'help_text' => 'Subtítulo de categorías',
                'order' => 6,
            ],
            [
                'section' => 'home',
                'key' => 'home.featured.title',
                'label' => 'Título Productos Destacados',
                'type' => 'text',
                'value' => 'Productos Destacados',
                'default_value' => 'Productos Destacados',
                'help_text' => 'Título de productos destacados',
                'order' => 7,
            ],
            [
                'section' => 'home',
                'key' => 'home.featured.subtitle',
                'label' => 'Subtítulo Destacados',
                'type' => 'text',
                'value' => 'Nuestros mejores productos seleccionados para ti',
                'default_value' => 'Nuestros mejores productos',
                'help_text' => 'Subtítulo de productos destacados',
                'order' => 8,
            ],

            // ===============================
            // PÁGINA ACERCA DE (7 items)
            // ===============================
            [
                'section' => 'about',
                'key' => 'about.title',
                'label' => 'Título de la Página',
                'type' => 'text',
                'value' => 'Acerca de Nosotros',
                'default_value' => 'Acerca de Nosotros',
                'help_text' => 'Título principal de la página',
                'order' => 1,
            ],
            [
                'section' => 'about',
                'key' => 'about.history.title',
                'label' => 'Título Historia',
                'type' => 'text',
                'value' => 'Nuestra Historia',
                'default_value' => 'Nuestra Historia',
                'help_text' => 'Título de la sección historia',
                'order' => 2,
            ],
            [
                'section' => 'about',
                'key' => 'about.history.content',
                'label' => 'Contenido Historia',
                'type' => 'textarea',
                'value' => '<p class="lead">B&R Tecnología es una empresa dedicada a ofrecer productos de alta calidad.</p>',
                'default_value' => '<p class="lead">B&R Tecnología es una empresa dedicada a ofrecer productos de alta calidad.</p>',
                'help_text' => 'Descripción de la historia (acepta HTML)',
                'order' => 3,
            ],
            [
                'section' => 'about',
                'key' => 'about.mission.title',
                'label' => 'Título Misión',
                'type' => 'text',
                'value' => 'Nuestra Misión',
                'default_value' => 'Nuestra Misión',
                'help_text' => 'Título de la sección misión',
                'order' => 4,
            ],
            [
                'section' => 'about',
                'key' => 'about.mission.content',
                'label' => 'Contenido Misión',
                'type' => 'textarea',
                'value' => 'Nuestra misión es ser tu proveedor de confianza, ofreciendo productos de calidad a precios accesibles.',
                'default_value' => 'Nuestra misión es ser tu proveedor de confianza.',
                'help_text' => 'Descripción de la misión',
                'order' => 5,
            ],
            [
                'section' => 'about',
                'key' => 'about.values.title',
                'label' => 'Título Valores',
                'type' => 'text',
                'value' => 'Nuestros Valores',
                'default_value' => 'Nuestros Valores',
                'help_text' => 'Título de la sección valores',
                'order' => 6,
            ],
            [
                'section' => 'about',
                'key' => 'about.values.content',
                'label' => 'Contenido Valores',
                'type' => 'textarea',
                'value' => '<li class="list-group-item">✓ <strong>Calidad:</strong> Solo ofrecemos productos de alta calidad.</li><li class="list-group-item">✓ <strong>Integridad:</strong> Transparencia en todas nuestras operaciones.</li><li class="list-group-item">✓ <strong>Servicio:</strong> Atención al cliente excepcional.</li><li class="list-group-item">✓ <strong>Innovación:</strong> Constantemente mejoramos nuestra plataforma.</li>',
                'default_value' => '<li class="list-group-item">✓ Calidad</li><li class="list-group-item">✓ Integridad</li>',
                'help_text' => 'Lista de valores (acepta HTML)',
                'order' => 7,
            ],

            // ===============================
            // INFORMACIÓN DE CONTACTO (6 items)
            // ===============================
            [
                'section' => 'contact',
                'key' => 'contact.title',
                'label' => 'Título de la Página',
                'type' => 'text',
                'value' => 'Contacto',
                'default_value' => 'Contacto',
                'help_text' => 'Título de la página de contacto',
                'order' => 1,
            ],
            [
                'section' => 'contact',
                'key' => 'contact.phone',
                'label' => 'Teléfono',
                'type' => 'text',
                'value' => '+593 98 863 3454',
                'default_value' => '+593 98 863 3454',
                'help_text' => 'Número de teléfono principal',
                'order' => 2,
            ],
            [
                'section' => 'contact',
                'key' => 'contact.address',
                'label' => 'Dirección',
                'type' => 'text',
                'value' => 'Machala, Ecuador',
                'default_value' => 'Machala, Ecuador',
                'help_text' => 'Dirección física',
                'order' => 3,
            ],
            [
                'section' => 'contact',
                'key' => 'contact.hours',
                'label' => 'Horario de Atención',
                'type' => 'text',
                'value' => 'Lun - Vie: 08:00 - 18:00',
                'default_value' => 'Lun - Vie: 08:00 - 18:00',
                'help_text' => 'Horario de atención',
                'order' => 4,
            ],
            [
                'section' => 'contact',
                'key' => 'contact.form.title',
                'label' => 'Título del Formulario',
                'type' => 'text',
                'value' => 'Envía tu Mensaje',
                'default_value' => 'Envía tu Mensaje',
                'help_text' => 'Título del formulario de contacto',
                'order' => 5,
            ],
            [
                'section' => 'contact',
                'key' => 'contact.social_facebook',
                'label' => 'Facebook URL',
                'type' => 'text',
                'value' => 'https://facebook.com',
                'default_value' => 'https://facebook.com',
                'help_text' => 'URL de Facebook',
                'order' => 6,
            ],
            [
                'section' => 'contact',
                'key' => 'contact.social_instagram',
                'label' => 'Instagram URL',
                'type' => 'text',
                'value' => 'https://instagram.com',
                'default_value' => 'https://instagram.com',
                'help_text' => 'URL de Instagram',
                'order' => 7,
            ],
            [
                'section' => 'contact',
                'key' => 'contact.social_twitter',
                'label' => 'Twitter/X URL',
                'type' => 'text',
                'value' => 'https://twitter.com',
                'default_value' => 'https://twitter.com',
                'help_text' => 'URL de Twitter',
                'order' => 8,
            ],

            // ===============================
            // BANNERS PROMOCIONALES (4 items)
            // ===============================
            [
                'section' => 'banners',
                'key' => 'banner.promo.enabled',
                'label' => 'Banner Habilitado',
                'type' => 'text',
                'value' => '0',
                'default_value' => '0',
                'help_text' => 'Usar 1 para activar, 0 para desactivar',
                'order' => 1,
            ],
            [
                'section' => 'banners',
                'key' => 'banner.promo.text',
                'label' => 'Texto del Banner',
                'type' => 'text',
                'value' => '¡Ofertas especiales esta semana!',
                'default_value' => '¡Ofertas especiales!',
                'help_text' => 'Texto promocional del banner',
                'order' => 2,
            ],
            [
                'section' => 'banners',
                'key' => 'banner.promo.link',
                'label' => 'Enlace del Banner',
                'type' => 'text',
                'value' => '/productos',
                'default_value' => '/productos',
                'help_text' => 'URL de destino al hacer clic',
                'order' => 3,
            ],
            [
                'section' => 'banners',
                'key' => 'banner.promo.bg_color',
                'label' => 'Color de Fondo',
                'type' => 'text',
                'value' => '#1a4d8c',
                'default_value' => '#1a4d8c',
                'help_text' => 'Color hexadecimal del fondo',
                'order' => 4,
            ],
        ];

        foreach ($contents as $content) {
            SiteContent::updateOrCreate(
                ['key' => $content['key']],
                $content
            );
        }

        $this->command->info('✅ Contenido del sitio creado: ' . count($contents) . ' elementos');
    }
}
