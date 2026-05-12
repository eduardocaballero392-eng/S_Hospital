#!/bin/bash

set -e
cd /var/www/html/backend || exit 1

# Ejecutar migraciones
php artisan migrate --force

# Usuarios demo (admin / médico): define SEED_DEMO_USERS=true en Render para crear credenciales de prueba
if [ "${SEED_DEMO_USERS:-}" = "true" ]; then
  php artisan db:seed --force --class="Database\\Seeders\\DemoUsersSeeder"
fi

# Optimizar
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar Apache
apache2-foreground
