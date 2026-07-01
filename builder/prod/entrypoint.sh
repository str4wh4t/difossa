#!/bin/sh
set -e

echo "Running Laravel production setup..."

echo "Installing composer dependencies..."
composer install --no-dev --optimize-autoloader

php artisan down --render="errors::503" --refresh=15 || true

echo "Running migrations..."
php artisan migrate --force

echo "Optimizing Filament..."
php artisan filament:assets
php artisan filament:optimize
php artisan optimize

echo "Npm setup..."
npm install
npm run build

echo "Fixing permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

php artisan storage:link || true

php artisan up

echo "Starting supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
