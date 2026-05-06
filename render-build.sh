#!/usr/bin/env bash
# Script de build para Render

set -e

echo "==> Instalando dependencias PHP..."
cd backend && composer install --no-dev --optimize-autoloader && cd ..

echo "==> Copiando .env..."
cp backend/.env.example backend/.env

echo "==> Generando APP_KEY..."
cd backend && php artisan key:generate --force && cd ..

echo "==> Instalando dependencias Node..."
npm install

echo "==> Compilando assets..."
npm run prod

echo "==> Ejecutando migraciones..."
cd backend && php artisan migrate --force && cd ..

echo "==> Limpiando y optimizando Laravel..."
cd backend && php artisan config:cache && php artisan route:cache && php artisan view:cache && cd ..

echo "==> Permisos de storage..."
chmod -R 775 backend/storage backend/bootstrap/cache

echo "✅ Build completado!"
