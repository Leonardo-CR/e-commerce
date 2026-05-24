#!/usr/bin/env bash
set -euo pipefail

echo "==> Esperando a la base de datos..."
ATTEMPTS=0
until php artisan db:show >/dev/null 2>&1; do
  ATTEMPTS=$((ATTEMPTS + 1))
  if [ "$ATTEMPTS" -ge 30 ]; then
    echo "!! No se pudo conectar a la base de datos tras 30 intentos."
    exit 1
  fi
  sleep 2
done

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
