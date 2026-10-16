# Guía de Despliegue: Backend en Render (Free Tier) + Neon + Frontend en Vercel

Esta guía explica los archivos creados en el proyecto de Laravel (`taskflow_pro`), para qué sirven y los pasos a seguir para desplegar tu Backend en **Render** (Free Tier) utilizando una base de datos de **Neon**, y conectarlo a un Frontend de React desplegado en **Vercel**.

---

## 1. Archivos Creados y su Propósito

### `render.yaml`
Es el archivo **Blueprint** (Infraestructura como código) de Render. Le dice a Render exactamente cómo configurar tu servidor (Web Service).
- Define que la aplicación usará Docker (`runtime: docker`).
- Asigna el plan gratuito (`plan: free`).
- Configura las variables de entorno principales.

### `Dockerfile`
Render no tiene soporte nativo gratuito para PHP puro; requiere un contenedor Docker.
Este archivo construye un sistema Ubuntu/Debian con **PHP 8.2**, **Nginx** (para servir la web) y las extensiones necesarias para conectarse a **PostgreSQL (Neon)**. Ejecuta `composer install` para instalar tus dependencias.

### `docker/nginx.conf`
Configura el servidor web (Nginx) dentro del contenedor. Asegura que todas las peticiones a la aplicación sean enrutadas a `public/index.php` de tu proyecto Laravel, que es como funciona el framework.

### `docker/start.sh`
Es el script de arranque del contenedor. Cuando el proyecto se despliega, este archivo se encarga de:
1. Iniciar el motor de PHP (`php-fpm`).
2. Limpiar cachés y configuraciones.
3. Ejecutar de forma automática las migraciones en la base de datos (`php artisan migrate --force`).
4. Iniciar Nginx para escuchar el tráfico web.

---

## 2. Configurar la Base de Datos en Neon

Render permitía bases de datos gratuitas pero caducan en 90 días, por lo que usar **Neon.tech** es la mejor opción gratuita y permanente (Serverless Postgres).

1. Ve a [Neon.tech](https://neon.tech/) y crea una cuenta.
2. Crea un nuevo proyecto. Neon te dará automáticamente un **Connection String** que se ve así:
   `postgres://usuario:password@ep-midominio.us-east-2.aws.neon.tech/neondb`
3. Guarda esa URL completa. Si la URL termina en `?sslmode=require`, asegúrate de incluirlo.

---

## 3. Desplegar el Frontend (React) en Vercel

Antes de subir el backend, necesitas saber qué dominio tendrá tu frontend.

1. Inicia sesión en [Vercel.com](https://vercel.com).
2. Crea un nuevo proyecto conectando el repositorio de GitHub donde tengas tu código React.
3. Vercel le asignará un dominio (ejemplo: `https://taskflow-react.vercel.app`).
4. (Opcional): Si tu frontend requiere la URL del backend, en Vercel configura una variable de entorno como `VITE_API_URL` (si usas Vite) apuntando a `https://laravel-task-api.onrender.com`.

---

## 4. Desplegar el Backend (Laravel) en Render

Ahora que tienes la URL de Neon y la URL de Vercel, procede con Render:

1. Asegúrate de hacer un `git commit` y un `git push` con todos los archivos creados (`render.yaml`, `Dockerfile`, `docker/*`) a tu repositorio de GitHub/GitLab.
2. Inicia sesión en [Render.com](https://render.com/).
3. Ve a la sección **Blueprints** en el menú principal.
4. Haz clic en **New Blueprint Instance**.
5. Conecta la cuenta de GitHub y selecciona el repositorio de `taskflow_pro`.
6. Render leerá automáticamente el archivo `render.yaml`. Durante este paso te pedirá que valides ciertos campos en pantalla.
7. **Importante**: En el Dashboard de Render, dirígete a la configuración de entorno (**Environment**) de tu nuevo Web Service y asegúrate de actualizar las siguientes variables de entorno:

### Variables a definir / verificar en Render

| Variable | Valor Esperado | Descripción |
|----------|----------------|-------------|
| `DB_CONNECTION` | `pgsql` | Motor de base de datos. **Debe** ser `pgsql` para Neon. Si no se define, Laravel cae a `sqlite` y fallará. |
| `DB_SSLMODE` | `require` | Neon exige conexión SSL. |
| `DATABASE_URL` | `postgres://usuario...?...sslmode=require` | El Connection String obtenido en Neon en el **Paso 2**. |
| `FRONTEND_URL` | `https://tu-proyecto.vercel.app` | La URL de tu Frontend React creada en el **Paso 3**. (Debe llevar `https://`) |
| `SANCTUM_STATEFUL_DOMAINS`| `tu-proyecto.vercel.app` | El dominio de tu frontend **SIN** `https://`. Permite las cookies de autenticación de Sanctum. |
| `APP_URL` | `https://laravel-task-api.onrender.com`| La URL pública que Render te asignó a este backend. |
| `APP_KEY` | (generado) | Render lo genera automáticamente (`generateValue: true`). |

> **Nota sobre el Plan Gratuito (Free Tier):** Los servicios web gratuitos en Render entran en "suspensión" (sleep) después de 15 minutos de inactividad. La primera petición después de dormir puede tardar unos 50 segundos en responder mientras el contenedor se despierta.

---

## 5. Diagnóstico de errores 500

`docker/start.sh` ahora usa `set -e`: si las migraciones fallan, el contenedor se detiene y el problema se ve en los **Logs** de Render. Si un endpoint de la API devuelve `500` solo cuando toca la base de datos (por ejemplo `POST /api/register` o `POST /api/login`) pero `GET /api/projects` responde `200`, el problema es la conexión a la base de datos, no CORS. Revisa:

1. Que `DB_CONNECTION=pgsql` y `DATABASE_URL` estén definidas en **Environment**.
2. Que el proyecto de Neon no esté pausado y que la URL incluya `?sslmode=require`.
3. Para ver el error exacto de forma temporal, pon `APP_DEBUG=true` (¡recuerda volver a `false` después!).

### Aplicar migraciones sin Shell (plan Free)

La **Shell** de Render solo está disponible en planes de pago. En la free tier las migraciones se aplican solas: `docker/start.sh` ejecuta `php artisan migrate --force` en cada arranque del contenedor, con reintentos para tolerar el arranque en frío de Neon. Por lo tanto:

- Tras cambiar cualquier variable de entorno, lanza un **Manual Deploy → Deploy latest commit** (o simplemente haz `git push`, que provoca un redeploy automático). El contenedor se reconstruye y vuelve a migrar.
- Si la migración falla, con `set -e` el contenedor se detiene y el error aparece en **Logs**. El endpoint `GET /up` (health check) deja de responder si el contenedor no levanta.
- Si prefieres aplicar las migraciones desde tu máquina contra Neon, necesitas `pdo_pgsql` y el cliente `postgresql`: `sudo apt install php8.5-pgsql postgresql-client`, y luego `DB_CONNECTION=pgsql DATABASE_URL='postgres://...?sslmode=require' php artisan migrate --force`.
