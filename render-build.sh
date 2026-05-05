#!/usr/bin/env bash
# Script de build para Render

set -e

echo "==> Instalando dependencias PHP..."
composer install --no-dev --optimize-autoloader

echo "==> Copiando .env..."
cp .env.example .env

echo "==> Generando APP_KEY..."
php artisan key:generate --force

echo "==> Instalando dependencias Node..."
npm install

echo "==> Compilando assets..."
npm run prod

echo "==> Limpiando y optimizando Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Permisos de storage..."
chmod -R 775 storage bootstrap/cache

echo "✅ Build completado!"
