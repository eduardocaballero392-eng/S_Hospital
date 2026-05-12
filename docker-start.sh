#!/bin/bash

set -e
cd /var/www/html/backend || exit 1

# Ejecutar migraciones
php artisan migrate --force

# Usuarios demo (admin / médico): por defecto se ejecuta en cada arranque (idempotente).
# En Render, pon SEED_DEMO_USERS=false si no quieres cuentas demo en producción.
case "${SEED_DEMO_USERS:-true}" in
  false|0|no|NO) ;;
  *)
    php artisan db:seed --force --class="Database\\Seeders\\DemoUsersSeeder"
    ;;
esac

# Optimizar
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar Apache
apache2-foreground
