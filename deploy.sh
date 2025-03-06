#!/bin/bash

cd /var/www/scbt || exit

echo "Pulling latest changes from GitHub..."
git pull origin main

echo "Running Composer install..."
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "Running migrations..."
php artisan migrate --force

echo "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan config:cache
php artisan view:clear
php artisan route:clear

echo "Restarting Apache..."
sudo systemctl restart apache2

echo "Deployment complete!"
