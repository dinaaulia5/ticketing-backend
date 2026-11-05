#!/bin/bash
set -e

cd /var/www/html

# Docker deployments don't ship a .env file (secrets come from Railway env
# vars). Some artisan commands (key:generate) still need *a* .env file to
# write to, so create a placeholder if missing. Real environment variables
# always take precedence over values in this file.
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate an APP_KEY on first boot if one wasn't provided via env vars.
if [ -z "$APP_KEY" ]; then
    echo "[entrypoint] APP_KEY not set, generating one..."
    php artisan key:generate --force
fi

# Cache config/routes/views for faster boot (safe to fail on first deploy).
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run database migrations automatically on every deploy.
php artisan migrate --force

# Make the public storage disk available (uploaded images, etc).
php artisan storage:link || true

echo "[entrypoint] Starting Laravel on port ${PORT:-8080}"
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
