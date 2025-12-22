# Dockerfile para Laravel en Render
FROM php:8.2-cli

# Instalar extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip gd bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Instalar dependencias de npm y compilar assets
RUN npm install && npm run build

# Crear directorios necesarios y permisos
RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Exponer puerto
EXPOSE 10000

# Comando de inicio
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    php artisan db:seed --class=AdminUserSeeder --force && \
    php -S 0.0.0.0:${PORT:-10000} -t public