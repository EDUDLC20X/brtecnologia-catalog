<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Esta migración:
     * 1. Elimina las tablas de facturación (orders, order_items)
     * 2. Crea la tabla site_contents para gestión de contenido
     */
    public function up(): void
    {
        // 1. Eliminar tablas de facturación
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');

        // 2. Crear tabla para contenido editable del sitio
        Schema::create('site_contents', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();           // Identificador único (ej: 'home.hero.title')
            $table->string('section');                  // Sección (ej: 'home', 'about', 'contact')
            $table->string('label');                    // Etiqueta para el admin (ej: 'Título del Hero')
            $table->enum('type', ['text', 'textarea', 'image', 'html'])->default('text');
            $table->text('value')->nullable();          // El contenido actual
            $table->text('default_value')->nullable();  // Valor por defecto
            $table->string('image_path')->nullable();   // Ruta de imagen si type=image
            $table->text('help_text')->nullable();      // Ayuda para el admin
            $table->integer('order')->default(0);       // Orden de visualización
            $table->timestamps();
            
            $table->index('section');
        });

        // 3. Insertar contenido inicial editable
        $this->seedInitialContent();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_contents');

        // Recrear tablas de orders si se revierte
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_number')->unique();
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Seed initial editable content
     */
    private function seedInitialContent(): void
    {
        $contents = [
            // ========== HOME PAGE ==========
            [
                'key' => 'home.hero.title',
                'section' => 'home',
                'label' => 'Título Principal (Hero)',
                'type' => 'text',
                'value' => 'Herramientas eléctricas, equipos industriales y tecnología',
                'default_value' => 'Herramientas eléctricas, equipos industriales y tecnología',
                'help_text' => 'El título grande que aparece en la sección principal de inicio',
                'order' => 1,
            ],
            [
                'key' => 'home.hero.subtitle',
                'section' => 'home',
                'label' => 'Subtítulo (Hero)',
                'type' => 'text',
                'value' => 'Su herramienta de trabajo en las mejores manos',
                'default_value' => 'Su herramienta de trabajo en las mejores manos',
                'help_text' => 'Texto secundario debajo del título principal',
                'order' => 2,
            ],
            [
                'key' => 'home.hero.image',
                'section' => 'home',
                'label' => 'Imagen Principal (Hero)',
                'type' => 'image',
                'value' => null,
                'default_value' => 'images/hero-product.png',
                'help_text' => 'Imagen destacada de la sección hero (recomendado: 800x600px)',
                'order' => 3,
            ],
            [
                'key' => 'home.hero.search_placeholder',
                'section' => 'home',
                'label' => 'Placeholder de Búsqueda',
                'type' => 'text',
                'value' => 'Buscar taladro, multímetro, robot, ...',
                'default_value' => 'Buscar taladro, multímetro, robot, ...',
                'help_text' => 'Texto de ejemplo en el campo de búsqueda',
                'order' => 4,
            ],
            [
                'key' => 'home.categories.title',
                'section' => 'home',
                'label' => 'Título Sección Categorías',
                'type' => 'text',
                'value' => 'Nuestras Categorías',
                'default_value' => 'Nuestras Categorías',
                'order' => 5,
            ],
            [
                'key' => 'home.categories.subtitle',
                'section' => 'home',
                'label' => 'Subtítulo Sección Categorías',
                'type' => 'text',
                'value' => 'Encuentra todo lo que necesitas para tu negocio',
                'default_value' => 'Encuentra todo lo que necesitas para tu negocio',
                'order' => 6,
            ],
            [
                'key' => 'home.featured.title',
                'section' => 'home',
                'label' => 'Título Productos Destacados',
                'type' => 'text',
                'value' => 'Productos Destacados',
                'default_value' => 'Productos Destacados',
                'order' => 7,
            ],
            [
                'key' => 'home.featured.subtitle',
                'section' => 'home',
                'label' => 'Subtítulo Productos Destacados',
                'type' => 'text',
                'value' => 'Nuestros mejores productos seleccionados para ti',
                'default_value' => 'Nuestros mejores productos seleccionados para ti',
                'order' => 8,
            ],

            // ========== ABOUT PAGE ==========
            [
                'key' => 'about.title',
                'section' => 'about',
                'label' => 'Título de la Página',
                'type' => 'text',
                'value' => 'Acerca de Nosotros',
                'default_value' => 'Acerca de Nosotros',
                'order' => 1,
            ],
            [
                'key' => 'about.history.title',
                'section' => 'about',
                'label' => 'Título Historia',
                'type' => 'text',
                'value' => 'Nuestra Historia',
                'default_value' => 'Nuestra Historia',
                'order' => 2,
            ],
            [
                'key' => 'about.history.content',
                'section' => 'about',
                'label' => 'Contenido Historia',
                'type' => 'html',
                'value' => '<p class="lead">B&R Tecnología es una empresa dedicada a ofrecer productos de alta calidad en herramientas eléctricas, equipos industriales y tecnología.</p><p>Desde nuestros inicios, hemos buscado proporcionar una experiencia excepcional a nuestros clientes, combinando variedad, calidad y servicio de primera clase.</p>',
                'default_value' => '<p class="lead">B&R Tecnología es una empresa dedicada a ofrecer productos de alta calidad en herramientas eléctricas, equipos industriales y tecnología.</p>',
                'order' => 3,
            ],
            [
                'key' => 'about.mission.title',
                'section' => 'about',
                'label' => 'Título Misión',
                'type' => 'text',
                'value' => 'Nuestra Misión',
                'default_value' => 'Nuestra Misión',
                'order' => 4,
            ],
            [
                'key' => 'about.mission.content',
                'section' => 'about',
                'label' => 'Contenido Misión',
                'type' => 'textarea',
                'value' => 'Nuestra misión es ser tu proveedor de confianza, ofreciendo una amplia selección de productos de calidad a precios accesibles, con un servicio al cliente excepcional.',
                'default_value' => 'Nuestra misión es ser tu proveedor de confianza.',
                'order' => 5,
            ],
            [
                'key' => 'about.values.title',
                'section' => 'about',
                'label' => 'Título Valores',
                'type' => 'text',
                'value' => 'Nuestros Valores',
                'default_value' => 'Nuestros Valores',
                'order' => 6,
            ],
            [
                'key' => 'about.values.content',
                'section' => 'about',
                'label' => 'Lista de Valores',
                'type' => 'html',
                'value' => '<li class="list-group-item">✓ <strong>Calidad:</strong> Solo ofrecemos productos de alta calidad.</li><li class="list-group-item">✓ <strong>Integridad:</strong> Transparencia en todas nuestras operaciones.</li><li class="list-group-item">✓ <strong>Servicio:</strong> Atención al cliente excepcional.</li><li class="list-group-item">✓ <strong>Innovación:</strong> Constantemente mejoramos nuestra plataforma.</li>',
                'default_value' => '<li class="list-group-item">✓ <strong>Calidad:</strong> Solo ofrecemos productos de alta calidad.</li>',
                'order' => 7,
            ],
            [
                'key' => 'about.image',
                'section' => 'about',
                'label' => 'Imagen de la Empresa',
                'type' => 'image',
                'value' => null,
                'default_value' => 'images/about-company.jpg',
                'help_text' => 'Imagen representativa de la empresa',
                'order' => 8,
            ],

            // ========== CONTACT PAGE ==========
            [
                'key' => 'contact.title',
                'section' => 'contact',
                'label' => 'Título de la Página',
                'type' => 'text',
                'value' => 'Contacto',
                'default_value' => 'Contacto',
                'order' => 1,
            ],
            [
                'key' => 'contact.phone',
                'section' => 'contact',
                'label' => 'Teléfono de Contacto',
                'type' => 'text',
                'value' => '+1 (555) 123-4567',
                'default_value' => '+1 (555) 123-4567',
                'order' => 2,
            ],
            [
                'key' => 'contact.hours',
                'section' => 'contact',
                'label' => 'Horario de Atención',
                'type' => 'text',
                'value' => 'Lun - Vie: 9am - 6pm',
                'default_value' => 'Lun - Vie: 9am - 6pm',
                'order' => 3,
            ],
            [
                'key' => 'contact.address',
                'section' => 'contact',
                'label' => 'Dirección',
                'type' => 'textarea',
                'value' => 'Calle Principal #123, Ciudad, País',
                'default_value' => 'Calle Principal #123, Ciudad, País',
                'order' => 4,
            ],
            [
                'key' => 'contact.whatsapp',
                'section' => 'contact',
                'label' => 'WhatsApp (número completo con código)',
                'type' => 'text',
                'value' => '',
                'default_value' => '',
                'help_text' => 'Ej: 5551234567 (sin + ni espacios)',
                'order' => 5,
            ],
            [
                'key' => 'contact.form.title',
                'section' => 'contact',
                'label' => 'Título del Formulario',
                'type' => 'text',
                'value' => 'Envía tu Mensaje',
                'default_value' => 'Envía tu Mensaje',
                'order' => 6,
            ],

            // ========== GLOBAL / BRANDING ==========
            [
                'key' => 'global.company_name',
                'section' => 'global',
                'label' => 'Nombre de la Empresa',
                'type' => 'text',
                'value' => 'B&R Tecnología',
                'default_value' => 'B&R Tecnología',
                'order' => 1,
            ],
            [
                'key' => 'global.tagline',
                'section' => 'global',
                'label' => 'Slogan / Tagline',
                'type' => 'text',
                'value' => 'Soluciones tecnológicas',
                'default_value' => 'Soluciones tecnológicas',
                'order' => 2,
            ],
            [
                'key' => 'global.logo',
                'section' => 'global',
                'label' => 'Logo Principal',
                'type' => 'image',
                'value' => null,
                'default_value' => 'images/logo.png',
                'help_text' => 'Logo de la empresa (formato PNG transparente recomendado)',
                'order' => 3,
            ],
            [
                'key' => 'global.logo_white',
                'section' => 'global',
                'label' => 'Logo Blanco (para fondos oscuros)',
                'type' => 'image',
                'value' => null,
                'default_value' => 'images/logo-white.png',
                'order' => 4,
            ],
            [
                'key' => 'global.footer_text',
                'section' => 'global',
                'label' => 'Texto del Footer',
                'type' => 'text',
                'value' => '© 2025 B&R Tecnología. Todos los derechos reservados.',
                'default_value' => '© 2025 B&R Tecnología. Todos los derechos reservados.',
                'order' => 5,
            ],

            // ========== BANNERS ==========
            [
                'key' => 'banner.promo.enabled',
                'section' => 'banners',
                'label' => 'Mostrar Banner Promocional',
                'type' => 'text',
                'value' => '0',
                'default_value' => '0',
                'help_text' => 'Escribe 1 para mostrar, 0 para ocultar',
                'order' => 1,
            ],
            [
                'key' => 'banner.promo.text',
                'section' => 'banners',
                'label' => 'Texto del Banner Promocional',
                'type' => 'text',
                'value' => '¡Nuevos productos disponibles! Consulta nuestro catálogo.',
                'default_value' => '¡Nuevos productos disponibles!',
                'order' => 2,
            ],
            [
                'key' => 'banner.promo.link',
                'section' => 'banners',
                'label' => 'Enlace del Banner',
                'type' => 'text',
                'value' => '/productos',
                'default_value' => '/productos',
                'order' => 3,
            ],
            [
                'key' => 'banner.promo.bg_color',
                'section' => 'banners',
                'label' => 'Color de Fondo del Banner',
                'type' => 'text',
                'value' => '#1a4d8c',
                'default_value' => '#1a4d8c',
                'help_text' => 'Código de color hexadecimal (ej: #1a4d8c)',
                'order' => 4,
            ],
        ];

        foreach ($contents as $content) {
            \DB::table('site_contents')->insert(array_merge($content, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
};
