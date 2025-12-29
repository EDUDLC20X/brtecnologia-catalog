# Sistema de Autenticación y Roles - B&R Tecnología

## Descripción General

El sistema de autenticación de B&R Tecnología implementa dos tipos de usuarios claramente diferenciados, cada uno con funcionalidades y permisos específicos diseñados para optimizar la experiencia de uso según el rol.

---

## Tipos de Usuario

### 1. Administrador (`is_admin = true`)

**Propósito:** Gestión completa del catálogo y el sistema.

**Capacidades:**
- ✅ Acceso total al panel de administración
- ✅ Gestión completa de productos (crear, editar, eliminar)
- ✅ Gestión de categorías con sistema de iconos predefinidos
- ✅ Gestión de imágenes de productos (integración con Cloudinary)
- ✅ Gestión de contenido del sitio (CMS)
- ✅ Acceso a diagnósticos del sistema
- ✅ Exportación de productos

**Rutas protegidas:** `/admin/*`  
**Middleware:** `auth`, `admin`

**Creación:** Los administradores se crean mediante el seeder `AdminUserSeeder` o directamente en la base de datos. No existe registro público para administradores.

---

### 2. Cliente (`is_admin = false`)

**Propósito:** Mejorar la experiencia del usuario mediante personalización y trazabilidad.

**Capacidades:**
- ✅ Registro público disponible
- ✅ Guardar productos como favoritos
- ✅ Historial de productos visualizados
- ✅ Realizar solicitudes de información/cotización
- ✅ Recomendaciones personalizadas basadas en historial
- ✅ Panel de cliente con resumen de actividad
- ✅ Gestión de preferencias de notificaciones

**Rutas protegidas:** `/mi-cuenta/*`  
**Middleware:** `auth`, `client`

---

## Justificación del Login de Cliente

El inicio de sesión para clientes no es obligatorio para navegar el catálogo, pero proporciona beneficios significativos:

### Beneficios para el Usuario:
1. **Favoritos persistentes:** Guardar productos de interés para acceso rápido
2. **Historial de navegación:** Retomar donde dejó su exploración
3. **Recomendaciones personalizadas:** Sugerencias basadas en sus intereses
4. **Solicitudes más rápidas:** Datos pre-llenados en formularios
5. **Seguimiento de solicitudes:** Ver estado de cotizaciones solicitadas

### Beneficios para el Negocio:
1. **Trazabilidad:** Entender el comportamiento de los clientes
2. **Análisis:** Identificar productos populares y patrones de navegación
3. **Marketing personalizado:** Base para futuras campañas segmentadas
4. **Mejora continua:** Datos para optimizar el catálogo

---

## Estructura de Base de Datos

### Tabla: `users` (extendida)
```
- id
- name
- email (único)
- password
- is_admin (boolean) ← Diferencia admin de cliente
- phone (opcional)
- avatar (opcional)
- notify_offers (preferencia)
- notify_new_products (preferencia)
- last_activity_at
- pending_email, email_change_token, email_change_requested_at (cambio de email)
- timestamps
```

### Tabla: `favorites`
```
- id
- user_id (FK)
- product_id (FK)
- notes (opcional)
- timestamps
```

### Tabla: `product_views`
```
- id
- user_id (FK)
- product_id (FK)
- view_count
- last_viewed_at
- timestamps
```

### Tabla: `product_requests`
```
- id
- user_id (FK, nullable)
- product_id (FK)
- name, email, phone, company
- quantity
- message
- status (pending, contacted, quoted, completed, cancelled)
- admin_notes
- responded_at
- timestamps
```

---

## Middlewares

| Middleware | Archivo | Descripción |
|------------|---------|-------------|
| `auth` | `Authenticate.php` | Verifica autenticación básica |
| `admin` | `EnsureUserIsAdmin.php` | Requiere `is_admin = true` |
| `client` | `EnsureUserIsClient.php` | Requiere `is_admin = false` |
| `track.views` | `TrackProductViews.php` | Registra vistas de productos |

---

## Rutas Principales

### Públicas
- `/` - Inicio
- `/productos` - Catálogo
- `/productos/{slug}` - Detalle de producto
- `/contact` - Formulario de contacto

### Autenticación
- `GET /login` - Formulario de login
- `POST /login` - Procesar login
- `GET /register` - Formulario de registro (clientes)
- `POST /register` - Procesar registro
- `POST /logout` - Cerrar sesión

### Cliente Autenticado (`/mi-cuenta/*`)
- `/mi-cuenta` - Dashboard del cliente
- `/mi-cuenta/favoritos` - Lista de favoritos
- `/mi-cuenta/historial` - Historial de navegación
- `/mi-cuenta/solicitudes` - Solicitudes realizadas
- `/mi-cuenta/recomendaciones` - Productos recomendados

### API de Favoritos (`/api/*`)
- `POST /api/favorites/{product}/toggle` - Agregar/quitar favorito
- `GET /api/favorites/{product}/check` - Verificar si es favorito
- `POST /api/favorites/check-multiple` - Verificar múltiples

### Administración (`/admin/*`)
- `/admin/dashboard` - Panel de control
- `/admin/categories/*` - CRUD de categorías
- `/admin/content/*` - Gestión de contenido
- `/products/*` - CRUD de productos

---

## Flujo de Autenticación

```
┌──────────────────┐
│   Usuario llega  │
│   al sitio       │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐     Sí      ┌──────────────────┐
│ ¿Está autenticado?├────────────►│ Verificar rol    │
└────────┬─────────┘             └────────┬─────────┘
         │ No                             │
         ▼                                ▼
┌──────────────────┐     ┌────────────────┴────────────────┐
│ Navega como      │     │                                 │
│ visitante        │     ▼                                 ▼
└──────────────────┘ ┌──────────┐                   ┌──────────┐
                     │is_admin=1│                   │is_admin=0│
                     │  ADMIN   │                   │ CLIENTE  │
                     └────┬─────┘                   └────┬─────┘
                          │                              │
                          ▼                              ▼
                     ┌──────────┐                   ┌──────────┐
                     │Redirige a│                   │Redirige a│
                     │/admin    │                   │/mi-cuenta│
                     │/dashboard│                   │          │
                     └──────────┘                   └──────────┘
```

---

## Seguridad

1. **Contraseñas:** Hasheadas con Bcrypt (Laravel default)
2. **CSRF:** Tokens en todos los formularios
3. **Rate Limiting:** 
   - Login: throttle:login
   - Registro: throttle:5,1
   - Solicitudes: throttle:5,1
4. **Validación:** Request validation en todos los endpoints
5. **Autorización:** Middlewares por ruta

---

## Escalabilidad

El sistema está preparado para futuras funcionalidades:

- [ ] Notificaciones por email de ofertas
- [ ] Carrito de cotizaciones
- [ ] Lista de deseos compartibles
- [ ] Comparador de productos
- [ ] Sistema de reseñas
- [ ] Niveles de cliente (básico, premium)

---

## Archivos Creados/Modificados

### Nuevos Archivos
- `database/migrations/2025_12_27_000001_add_client_fields_to_users_table.php`
- `database/migrations/2025_12_27_000002_create_favorites_table.php`
- `database/migrations/2025_12_27_000003_create_product_views_table.php`
- `database/migrations/2025_12_27_000004_create_product_requests_table.php`
- `app/Models/Favorite.php`
- `app/Models/ProductView.php`
- `app/Models/ProductRequest.php`
- `app/Http/Controllers/Auth/RegisteredUserController.php`
- `app/Http/Controllers/Client/ClientDashboardController.php`
- `app/Http/Controllers/Client/FavoriteController.php`
- `app/Http/Controllers/Client/ProductHistoryController.php`
- `app/Http/Controllers/Client/ProductRequestController.php`
- `app/Http/Middleware/EnsureUserIsClient.php`
- `app/Http/Middleware/TrackProductViews.php`
- `resources/views/auth/register.blade.php`
- `resources/views/client/dashboard.blade.php`
- `resources/views/client/favorites/index.blade.php`
- `resources/views/client/history/index.blade.php`
- `resources/views/client/requests/index.blade.php`

### Archivos Modificados
- `app/Models/User.php` - Agregadas relaciones y métodos de cliente
- `app/Http/Kernel.php` - Registrados nuevos middlewares
- `routes/web.php` - Nuevas rutas de cliente
- `routes/auth.php` - Ruta de registro
- `resources/views/layouts/navigation.blade.php` - Menú diferenciado por rol
- `resources/views/auth/login.blade.php` - Link a registro
- `resources/views/catalog/show.blade.php` - Botón de favoritos

---

## Comandos de Instalación

```bash
# Ejecutar migraciones
php artisan migrate

# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# (Opcional) Crear admin inicial
php artisan db:seed --class=AdminUserSeeder
```

---

*Documentación generada el 27 de diciembre de 2025*
*Sistema desarrollado para B&R Tecnología - Prácticas Preprofesionales*
