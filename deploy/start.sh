#!/usr/bin/env bash
set -euo pipefail

# Habilitar workers múltiples para php artisan serve (evita bloqueos de assets)
export PHP_CLI_SERVER_WORKERS="${PHP_CLI_SERVER_WORKERS:-4}"

echo "==> Esperando a la base de datos..."
ATTEMPTS=0
MAX_ATTEMPTS=15
while true; do
  # Ejecutamos db:show y capturamos la salida de error si falla
  DB_ERRORS=$(php artisan db:show 2>&1 >/dev/null) && break

  ATTEMPTS=$((ATTEMPTS + 1))
  echo "Intento $ATTEMPTS/$MAX_ATTEMPTS: La base de datos no está lista aún..."
  if [ "$ATTEMPTS" -ge "$MAX_ATTEMPTS" ]; then
    echo "=========================================================="
    echo "!! ERROR: No se pudo conectar a la base de datos."
    echo "Detalle del error de Laravel:"
    echo "$DB_ERRORS"
    echo "----------------------------------------------------------"
    echo "CONSEJO DE DESPLIEGUE:"
    echo "Asegúrate de configurar las variables de conexión en Railway."
    echo "Si usas MySQL de Railway, mapea las siguientes variables de entorno:"
    echo "  DB_CONNECTION = mysql"
    echo "  DB_HOST = \${MYSQLHOST}"
    echo "  DB_PORT = \${MYSQLPORT}"
    echo "  DB_DATABASE = \${MYSQLDATABASE}"
    echo "  DB_USERNAME = \${MYSQLUSER}"
    echo "  DB_PASSWORD = \${MYSQLPASSWORD}"
    echo "=========================================================="
    exit 1
  fi
  sleep 3
done

echo "==> Limpiando archivos temporales de desarrollo..."
rm -f public/hot

echo "==> Cacheando configuracion, rutas y vistas..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Asegurando symlink de storage..."
php artisan storage:link || true

echo "==> Ejecutando migrate:fresh --seed (DESTRUCTIVO)..."
php artisan migrate:fresh --seed --force

echo "==> Levantando servidor en puerto ${PORT:-8000}..."
exec php artisan serve --host 0.0.0.0 --port "${PORT:-8000}"
