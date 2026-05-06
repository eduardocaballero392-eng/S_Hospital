#!/bin/bash

set -e
cd /var/www/html/backend || exit 1

# Ejecutar migraciones
php artisan migrate --force

# Optimizar
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar Apache
apache2-foreground
