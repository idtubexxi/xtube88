#!/bin/bash
echo "🚀 Deploying XTube..."

cd /var/www/stream.xtubes.site

# Pull latest changes
git pull origin main

# Build and start container
docker compose down
docker compose build --no-cache
docker compose up -d

# Wait for container to be healthy
echo "⏳ Waiting for container to be healthy..."
for i in {1..30}; do
    if docker compose ps app | grep -q "healthy"; then
        echo "✅ Container is healthy"
        break
    fi
    sleep 2
done

# Run Laravel optimizations
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
docker compose exec app php artisan storage:link

echo "✅ Deployment completed!"
