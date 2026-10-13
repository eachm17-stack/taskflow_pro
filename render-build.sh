#!/usr/bin/env bash
# render-build.sh
set -e
composer install --no-dev --optimize-autoloader
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache

php artisan migrate --force