#!/bin/sh
set -e

echo "Running Laravel setup..."

if [ ! -f .env ]; then
    cp .env.example .env
fi

echo "Installing composer dependencies..."
composer install

php artisan down --render="errors::503" --refresh=15 || true

if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate
fi

echo "Running migrations..."
php artisan migrate --force --seed

echo "Publishing Filament assets..."
php artisan filament:assets

echo "Npm setup..."
npm install

php artisan storage:link || true

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

php artisan up

echo "Starting supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
