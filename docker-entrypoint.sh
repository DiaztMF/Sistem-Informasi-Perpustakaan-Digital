#!/bin/sh
set -e

# Ensure SQLite file exists and has correct permissions
touch /app/database/database.sqlite
chown -R www-data:www-data /app/database /app/storage /app/bootstrap/cache
chmod -R 775 /app/database /app/storage /app/bootstrap/cache

# Generate APP_KEY if not provided
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force || true
fi

# Run database migrations
php artisan migrate --force || true

exec "$@"
