#!/usr/bin/env bash
# render-build.sh
set -e
composer install --no-dev --optimize-autoloader
php artisan config:clear
php artisan migrate --force