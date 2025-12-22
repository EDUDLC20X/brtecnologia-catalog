# 🛒 B&R Tecnología - Catálogo de Productos

<p align="center">
  <img src="public/images/logo.png" alt="B&R Tecnología Logo" width="200">
</p>

<p align="center">
  <strong>Catálogo digital para herramientas eléctricas, equipos industriales y tecnología</strong>
</p>

---

## 📋 Tabla de Contenidos

- [Descripción](#-descripción)
- [Stack Tecnológico](#-stack-tecnológico)
- [Requisitos](#-requisitos)
- [Instalación](#-instalación)
- [Configuración](#-configuración)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Funcionalidades](#-funcionalidades)
- [Gestión de Contenido](#-gestión-de-contenido)
- [API REST](#-api-rest)
- [Testing](#-testing)
- [Despliegue](#-despliegue)

---

## 📝 Descripción

**B&R Tecnología** es una plataforma de catálogo digital desarrollada con Laravel 9, diseñada para mostrar productos tecnológicos e industriales. Incluye catálogo de productos con búsqueda avanzada, sistema de solicitudes de información, wishlist, reviews, panel de administración y **gestión de contenido (CMS)** para editar textos e imágenes de las páginas públicas.

> ℹ️ **Nota:** Esta es una plataforma de catálogo/showroom. No incluye funcionalidades de carrito de compras ni procesamiento de pagos. Los clientes pueden solicitar información sobre productos a través de formularios de contacto.

---

## 🛠 Stack Tecnológico

| Componente | Tecnología | Versión |
|------------|------------|---------|
| **Backend** | Laravel | 9.19 |
| **PHP** | PHP | 8.0.2+ |
| **Base de Datos** | PostgreSQL | 12+ |
| **Frontend** | Blade + Bootstrap | 5.x |
| **Bundler** | Vite | 4.x |
| **Autenticación** | Laravel Breeze + Sanctum | 3.0 |
| **Email** | Gmail SMTP | - |

---

## 📦 Requisitos

- PHP >= 8.0.2
- Composer >= 2.0
- Node.js >= 16.x
- PostgreSQL >= 12
- Extensiones PHP: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath

---

## 🚀 Instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/tu-usuario/ecommerce-br-v2.git
cd ecommerce-br-v2

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias Node.js
npm install

# 4. Copiar archivo de configuración
cp .env.example .env

# 5. Generar clave de aplicación
php artisan key:generate

# 6. Configurar base de datos en .env (ver sección Configuración)

# 7. Ejecutar migraciones
php artisan migrate

# 8. Ejecutar seeders (categorías por defecto)
php artisan db:seed

# 9. Crear enlace simbólico para storage
php artisan storage:link

# 10. Compilar assets (desarrollo)
npm run dev

# 11. Iniciar servidor
php artisan serve
```

---

## ⚙ Configuración

### Variables de Entorno (.env)

```env
# Aplicación
APP_NAME="B&R Tecnología"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de Datos (PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=ecommerce_br_v2
DB_USERNAME=postgres
DB_PASSWORD=tu_password

# Email SMTP (Gmail)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu_email@gmail.com

# Admin (emails separados por coma)
ADMIN_EMAILS=admin@tudominio.com
```

---

## 📁 Estructura del Proyecto

```
ecommerce-br-v2/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/                 # Controladores API REST
│   │   │   ├── Admin/               # Controladores de administración
│   │   │   │   └── ContentController.php  # CMS
│   │   │   ├── ProductCatalogController.php
│   │   │   ├── WishlistController.php
│   │   │   └── ReviewController.php
│   │   └── Middleware/
│   │       └── EnsureUserIsAdmin.php
│   ├── Models/                      # Modelos Eloquent
│   │   ├── SiteContent.php          # CMS contenido editable
│   │   └── ...
│   ├── Mail/                        # Mailables
│   └── Jobs/                        # ProductsExportJob
├── database/
│   ├── migrations/                  # Migraciones
│   └── seeders/                     # CategorySeeder
├── resources/views/
│   ├── layouts/                     # Templates base
│   ├── catalog/                     # Catálogo público
│   ├── admin/                       # Panel administración
│   │   └── content/                 # CMS views
│   └── emails/                      # Plantillas de email
├── routes/
│   ├── web.php                      # Rutas web
│   └── api.php                      # API REST v1
├── docs/
│   └── openapi.yaml                 # Documentación API
├── tests/
│   ├── Feature/                     # Tests de integración
│   └── Unit/                        # Tests unitarios
└── public/
    ├── css/
    └── images/
```

---

## ✨ Funcionalidades

### 🛍 Catálogo de Productos
- Búsqueda por nombre, SKU, descripción
- Filtros por categoría y rango de precios
- Ordenamiento: precio, nombre, fecha, rating
- Paginación con alto rendimiento
- Solicitud de información por producto

### 📧 Sistema de Contacto
- Formulario de contacto general
- Solicitudes de información por producto
- Confirmación automática por email

### ❤️ Wishlist
- Agregar/remover productos favoritos
- Requiere autenticación

### ⭐ Reviews
- Sistema de calificación 1-5 estrellas
- Título y comentario
- Promedio calculado por producto

### 👤 Autenticación
- Registro/Login de usuarios
- Verificación de email
- Roles: Cliente / Admin

### 🔧 Panel de Administración
- CRUD de productos
- **Gestión de contenido (CMS)** - Editar textos e imágenes de páginas públicas
- Exportación CSV (async)
- Dashboard con estadísticas

---

## 📝 Gestión de Contenido

El sistema incluye un **CMS integrado** que permite al administrador editar el contenido de las páginas públicas sin modificar código.

### Secciones Editables

| Sección | Contenido |
|---------|-----------|
| **Global** | Logo, nombre de empresa, tagline, footer |
| **Inicio** | Hero (título, subtítulo, imagen), categorías, productos destacados |
| **Acerca de** | Historia, misión, valores, imagen |
| **Contacto** | Teléfono, horario, dirección, WhatsApp |
| **Banners** | Banner promocional (texto, color, enlace) |

### Acceso

1. Iniciar sesión como administrador
2. Ir a **Panel Admin** → **Gestionar Contenido**
3. Seleccionar sección a editar
4. Guardar cambios

Los cambios se reflejan inmediatamente en las páginas públicas.

---

## 🔌 API REST

Base URL: `/api/v1`

### Endpoints Públicos

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/products` | Listar productos |
| GET | `/products/search?q=` | Buscar productos |
| GET | `/products/{id}` | Detalle de producto |
| GET | `/categories` | Listar categorías |
| GET | `/categories/{id}/products` | Productos por categoría |

### Endpoints Autenticados (Bearer Token)

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/user` | Usuario actual |
| GET | `/products/{id}/reviews` | Reviews de producto |
| POST | `/reviews` | Crear review |
| DELETE | `/reviews/{id}` | Eliminar review |

### Documentación
- **OpenAPI Spec**: `/api/docs` (YAML)
- **Colección Postman**: `Postman_API_Collection.json`

---

## 🧪 Testing

```bash
# Ejecutar todos los tests
php artisan test

# Tests específicos
php artisan test --filter=ProductFlowTest
php artisan test --filter=ProductIdempotencyTest

# Con coverage
php artisan test --coverage
```

### Tests Disponibles
- `ProductFlowTest` - Flujo completo de productos
- `ProductIdempotencyTest` - Prevención de duplicados
- `ProductValidationTest` - Validaciones
- `ProfileTest` - Perfil de usuario

---

## 🚀 Despliegue

### Preparación para Producción

```bash
# 1. Configurar .env
APP_ENV=production
APP_DEBUG=false

# 2. Optimizar
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev

# 3. Compilar assets
npm run build

# 4. Migrar base de datos
php artisan migrate --force
```

### Recomendaciones
- Usar Redis para `CACHE_DRIVER` y `QUEUE_CONNECTION`
- Configurar HTTPS obligatorio
- Implementar rate limiting
- Usar S3/CDN para imágenes en producción

---

## 📄 Licencia

Este proyecto está bajo la Licencia MIT.

---

## 👥 Autores

- **Eduardo De La Cruz** - Desarrollo principal

---

<p align="center">
  <sub>Desarrollado con ❤️ usando Laravel</sub>
</p>
