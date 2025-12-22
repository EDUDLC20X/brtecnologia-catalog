# B&R Tecnología - Catálogo de Productos

Sistema web de catálogo de productos desarrollado con Laravel para la empresa B&R Tecnología.

## Despliegue en Render

### Requisitos previos
- Cuenta en [Render](https://render.com)
- Repositorio Git (GitHub, GitLab)

### Pasos para desplegar

#### 1. Subir el código a GitHub
```bash
git init
git add .
git commit -m "Preparar para despliegue en Render"
git branch -M main
git remote add origin https://github.com/TU_USUARIO/brtecnologia-catalog.git
git push -u origin main
```

#### 2. Crear Base de Datos en Render
1. Ir a [Render Dashboard](https://dashboard.render.com)
2. Click en "New" → "PostgreSQL"
3. Configurar:
   - Name: `brtecnologia-db`
   - Database: `brtecnologia`
   - User: `brtecnologia`
   - Region: Oregon (US West)
   - Plan: Free
4. Click "Create Database"
5. Copiar la "Internal Database URL" para usarla después

#### 3. Crear Web Service en Render
1. Click en "New" → "Web Service"
2. Conectar tu repositorio de GitHub
3. Configurar:
   - Name: `brtecnologia-catalog`
   - Region: Oregon (US West)
   - Branch: main
   - Runtime: PHP
   - Build Command: `chmod +x build.sh && ./build.sh`
   - Start Command: `heroku-php-apache2 public/`
   - Plan: Free

#### 4. Configurar Variables de Entorno
En la sección "Environment" del Web Service, agregar:

| Variable | Valor |
|----------|-------|
| `APP_NAME` | `B&R Tecnología` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | `base64:GENERAR_CON_php_artisan_key:generate` |
| `APP_URL` | `https://TU-APP.onrender.com` |
| `LOG_CHANNEL` | `stderr` |
| `DB_CONNECTION` | `pgsql` |
| `DATABASE_URL` | `(Internal Database URL de paso 2)` |
| `SESSION_DRIVER` | `cookie` |
| `SESSION_SECURE_COOKIE` | `true` |
| `CACHE_DRIVER` | `file` |
| `QUEUE_CONNECTION` | `sync` |
| `MAIL_MAILER` | `log` |

#### 5. Desplegar
- Click en "Create Web Service"
- Esperar a que termine el build (5-10 minutos primera vez)
- Una vez completado, acceder a la URL proporcionada

### Configuración del Administrador

Después del primer despliegue, crear el usuario administrador:

1. Ir a Render Dashboard → Tu Web Service → Shell
2. Ejecutar:
```bash
php artisan tinker
```
3. Crear administrador:
```php
\App\Models\User::create([
    'name' => 'Administrador',
    'email' => 'admin@brtecnologia.ec',
    'password' => bcrypt('tu_contraseña_segura'),
    'is_admin' => true,
    'email_verified_at' => now()
]);
```

### URLs del Sistema

- **Catálogo público**: `https://TU-APP.onrender.com`
- **Login admin**: `https://TU-APP.onrender.com/login`
- **Dashboard admin**: `https://TU-APP.onrender.com/admin/dashboard`

### Notas importantes

- El plan gratuito de Render pone el servicio en "sleep" después de 15 minutos de inactividad
- La primera carga después del sleep toma ~30 segundos
- La base de datos gratuita expira después de 90 días
- Para proyectos de demostración/académicos es suficiente

## Desarrollo Local

```bash
# Instalar dependencias
composer install
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env
# Ejecutar migraciones
php artisan migrate

# Compilar assets
npm run dev

# Iniciar servidor
php artisan serve
```

## Tecnologías

- **Backend**: Laravel 9.x
- **Frontend**: Blade + Bootstrap 5
- **Base de datos**: PostgreSQL
- **Estilos**: CSS personalizado
