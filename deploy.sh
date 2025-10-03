#!/bin/bash
echo "🚀 Starting Railway deployment..."

# Install dependencies
composer install --no-dev --optimize-autoloader

# Generate key jika belum ada
# php artisan key:generate --force

# Run migrations
# php artisan migrate --force

# Create storage link
php artisan storage:link

# Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment completed!"
