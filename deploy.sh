#!/bin/bash
# ═══════════════════════════════════════════════════════════════
# Script de Despliegue - B&R Tecnología E-commerce
# ═══════════════════════════════════════════════════════════════

echo "╔══════════════════════════════════════════════════════════════╗"
echo "║     🚀 DESPLIEGUE - B&R Tecnología E-commerce               ║"
echo "╚══════════════════════════════════════════════════════════════╝"
echo ""

# Colores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Función para mostrar estado
show_status() {
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓${NC} $1"
    else
        echo -e "${RED}✗${NC} $1 - ERROR"
        exit 1
    fi
}

echo "┌─ PASO 1: DEPENDENCIAS ─────────────────────────────────────"
echo "Instalando dependencias de Composer (producción)..."
composer install --optimize-autoloader --no-dev
show_status "Dependencias de Composer instaladas"

echo ""
echo "┌─ PASO 2: MIGRACIONES ──────────────────────────────────────"
echo "Ejecutando migraciones..."
php artisan migrate --force
show_status "Migraciones ejecutadas"

echo ""
echo "┌─ PASO 3: SEEDERS ──────────────────────────────────────────"
# Solo ejecutar seeders si la base de datos está vacía
PRODUCT_COUNT=$(php artisan tinker --execute="echo \App\Models\Product::count()")
if [ "$PRODUCT_COUNT" -eq "0" ]; then
    echo "Base de datos vacía, ejecutando seeders..."
    php artisan db:seed --force
    show_status "Seeders ejecutados"
else
    echo -e "${YELLOW}⚠${NC} Base de datos ya tiene datos ($PRODUCT_COUNT productos), omitiendo seeders"
fi

echo ""
echo "┌─ PASO 4: ALMACENAMIENTO ───────────────────────────────────"
echo "Creando symlink de storage..."
php artisan storage:link 2>/dev/null || echo -e "${YELLOW}⚠${NC} Symlink ya existe"
show_status "Storage configurado"

echo ""
echo "┌─ PASO 5: CACHÉ Y OPTIMIZACIÓN ─────────────────────────────"
echo "Limpiando cachés anteriores..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "Generando nuevos cachés optimizados..."
php artisan config:cache
show_status "Caché de configuración"
php artisan route:cache
show_status "Caché de rutas"
php artisan view:cache
show_status "Caché de vistas"

echo ""
echo "┌─ PASO 6: ASSETS ───────────────────────────────────────────"
if [ -f "package.json" ]; then
    echo "Verificando assets compilados..."
    if [ -d "public/build" ]; then
        echo -e "${GREEN}✓${NC} Assets ya compilados en public/build"
    else
        echo "Compilando assets con Vite..."
        npm ci
        npm run build
        show_status "Assets compilados"
    fi
else
    echo -e "${YELLOW}⚠${NC} No se encontró package.json, omitiendo compilación de assets"
fi

echo ""
echo "┌─ PASO 7: VERIFICACIÓN FINAL ───────────────────────────────"
echo "Ejecutando verificación del sistema..."
php artisan system:verify

echo ""
echo "╔══════════════════════════════════════════════════════════════╗"
echo "║     ✅ DESPLIEGUE COMPLETADO                                ║"
echo "╚══════════════════════════════════════════════════════════════╝"
echo ""
echo "📋 Checklist post-despliegue:"
echo "   [ ] Verificar APP_DEBUG=false en .env"
echo "   [ ] Verificar APP_ENV=production en .env"
echo "   [ ] Configurar SSL/HTTPS"
echo "   [ ] Configurar backups de base de datos"
echo "   [ ] Monitorear logs en storage/logs/"
echo ""
