#!/bin/bash
set -e

echo "🚀 Starting Laravel on Render.com..."

# Wait for database
if [ ! -z "$DB_HOST" ]; then
    echo "⏳ Waiting for database..."
    until nc -z -v -w30 $DB_HOST $DB_PORT 2>/dev/null; do
        echo "Waiting for database connection..."
        sleep 2
    done
    echo "✅ Database is ready!"
fi

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating APP_KEY..."
    php artisan key:generate --force --no-interaction
fi

# Run migrations
echo "📊 Running migrations..."
php artisan migrate --force --no-interaction

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link || true

# Clear and cache config
echo "🗑️ Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "💾 Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
echo "🔒 Setting permissions..."
chown -R www-data:www-data /app/storage /app/bootstrap/cache
chmod -R 775 /app/storage /app/bootstrap/cache

echo "✅ Laravel is ready!"

# Execute CMD
exec "$@"
