#!/bin/bash

# Build script para Render - B&R Tecnología
echo "=== Instalando dependencias de Composer ==="
composer install --no-dev --optimize-autoloader

echo "=== Instalando dependencias de NPM ==="
npm install

echo "=== Compilando assets ==="
npm run build

echo "=== Configurando permisos de storage ==="
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "=== Creando enlace simbólico de storage ==="
php artisan storage:link --force || true

echo "=== Ejecutando migraciones ==="
php artisan migrate --force

echo "=== Creando usuario administrador ==="
php artisan db:seed --class=AdminUserSeeder --force

echo "=== Limpiando y cacheando configuración ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Build completado ==="
echo "📧 Admin: lcruz276_est@instipp.edu.ec"
echo "🔑 Pass: Admin2025BR""
