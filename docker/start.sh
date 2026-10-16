#!/bin/bash
# Arranque del contenedor: PHP-FPM + cachés + migraciones + Nginx.
#
# Las migraciones se ejecutan aquí en cada arranque. En la free tier de Render
# no hay Shell, así que este es el mecanismo para aplicar la base de datos: un
# redeploy vuelve a correr este script.
#
# Con "set -e" el contenedor se detiene si algo falla, de modo que el despliegue
# falle de forma visible (en los Logs de Render) en vez de arrancar con la base
# de datos rota y devolver 500 en cada petición.
set -e

echo "==> Iniciando PHP-FPM"
php-fpm -D

echo "==> Generando cachés de Laravel"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Ejecutando migraciones"
attempt=1
max_attempts=5
# Reintenta ante arranques en frío de Neon o fallos transitorios de conexión.
until php artisan migrate --force; do
    if [ "$attempt" -ge "$max_attempts" ]; then
        echo "ERROR: las migraciones fallaron tras ${max_attempts} intentos. Revise DATABASE_URL / DB_CONNECTION / DB_SSLMODE." >&2
        exit 1
    fi
    echo "Intento ${attempt} fallido; reintentando en 5s..."
    attempt=$((attempt + 1))
    sleep 5
done

echo "==> Iniciando Nginx"
exec nginx -g "daemon off;"
