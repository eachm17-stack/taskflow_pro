#!/bin/bash

# Iniciar PHP-FPM en segundo plano
php-fpm -D

# Correr migraciones y limpiar cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

# Iniciar Nginx en primer plano
nginx -g "daemon off;"
