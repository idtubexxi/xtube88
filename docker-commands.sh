#!/bin/bash

# Quick Docker Commands for Laravel
# Usage: ./docker-commands.sh [command]

case "$1" in
    "start")
        echo "🚀 Starting containers..."
        docker compose up -d
        ;;

    "stop")
        echo "🛑 Stopping containers..."
        docker compose down
        ;;

    "restart")
        echo "🔄 Restarting containers..."
        docker compose restart
        ;;

    "logs")
        echo "📋 Showing logs (Ctrl+C to exit)..."
        docker compose logs -f app
        ;;

    "shell")
        echo "🐚 Entering container shell..."
        docker compose exec app bash
        ;;

    "migrate")
        echo "📊 Running migrations..."
        docker compose exec app php artisan migrate
        ;;

    "migrate-fresh")
        echo "⚠️  Running fresh migrations (will delete all data)..."
        read -p "Are you sure? (y/N) " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            docker compose exec app php artisan migrate:fresh
        fi
        ;;

    "cache-clear")
        echo "🗑️  Clearing all caches..."
        docker compose exec app php artisan config:clear
        docker compose exec app php artisan cache:clear
        docker compose exec app php artisan view:clear
        docker compose exec app php artisan route:clear
        echo "✅ Caches cleared!"
        ;;

    "cache")
        echo "💾 Caching configuration..."
        docker compose exec app php artisan config:cache
        docker compose exec app php artisan route:cache
        docker compose exec app php artisan view:cache
        echo "✅ Configuration cached!"
        ;;

    "optimize")
        echo "⚡ Optimizing application..."
        docker compose exec app php artisan optimize
        ;;

    "storage-link")
        echo "🔗 Creating storage link..."
        docker compose exec app php artisan storage:link
        ;;

    "permissions")
        echo "🔒 Fixing permissions..."
        docker compose exec app chown -R www-data:www-data /app/storage /app/bootstrap/cache
        docker compose exec app chmod -R 775 /app/storage /app/bootstrap/cache
        echo "✅ Permissions fixed!"
        ;;

    "tinker")
        echo "🔧 Starting Tinker..."
        docker compose exec app php artisan tinker
        ;;

    "test")
        echo "🧪 Running tests..."
        docker compose exec app php artisan test
        ;;

    "db-show")
        echo "🗄️  Database information..."
        docker compose exec app php artisan db:show
        ;;

    "composer-install")
        echo "📦 Installing composer dependencies..."
        docker compose exec app composer install
        ;;

    "composer-update")
        echo "⬆️  Updating composer dependencies..."
        docker compose exec app composer update
        ;;

    "rebuild")
        echo "🏗️  Rebuilding container..."
        docker compose down
        docker compose build --no-cache
        docker compose up -d
        ;;

    "stats")
        echo "📊 Container statistics..."
        docker stats stream-xtube-app
        ;;

    "health")
        echo "🏥 Checking application health..."
        HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/health.php)
        if [ "$HTTP_CODE" = "200" ]; then
            echo "✅ Application is healthy (HTTP $HTTP_CODE)"
        else
            echo "❌ Application is unhealthy (HTTP $HTTP_CODE)"
        fi
        ;;

    "backup-db")
        echo "💾 Creating database backup..."
        TIMESTAMP=$(date +%Y%m%d_%H%M%S)
        docker compose exec app php artisan db:backup backup_$TIMESTAMP.sql
        echo "✅ Backup created: backup_$TIMESTAMP.sql"
        ;;

    "clean")
        echo "🧹 Cleaning Docker system..."
        docker system prune -f
        ;;

    "update")
        echo "📥 Pulling latest changes and redeploying..."
        git pull origin main
        ./deploy.sh
        ;;

    *)
        echo "🚀 Laravel Docker Quick Commands"
        echo ""
        echo "Usage: ./docker-commands.sh [command]"
        echo ""
        echo "Available commands:"
        echo "  start              - Start containers"
        echo "  stop               - Stop containers"
        echo "  restart            - Restart containers"
        echo "  logs               - Show container logs"
        echo "  shell              - Enter container shell"
        echo "  migrate            - Run database migrations"
        echo "  migrate-fresh      - Fresh migrations (⚠️ deletes data)"
        echo "  cache-clear        - Clear all caches"
        echo "  cache              - Cache configuration"
        echo "  optimize           - Optimize application"
        echo "  storage-link       - Create storage symbolic link"
        echo "  permissions        - Fix storage permissions"
        echo "  tinker             - Start Laravel Tinker"
        echo "  test               - Run tests"
        echo "  db-show            - Show database info"
        echo "  composer-install   - Install composer dependencies"
        echo "  composer-update    - Update composer dependencies"
        echo "  rebuild            - Rebuild container from scratch"
        echo "  stats              - Show container statistics"
        echo "  health             - Check application health"
        echo "  clean              - Clean Docker system"
        echo "  update             - Pull changes and redeploy"
        echo ""
        echo "Examples:"
        echo "  ./docker-commands.sh start"
        echo "  ./docker-commands.sh logs"
        echo "  ./docker-commands.sh cache-clear"
        ;;
esac
