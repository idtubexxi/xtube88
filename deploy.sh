#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Laravel Docker Deployment Script${NC}"
echo -e "${GREEN}========================================${NC}\n"

# Detect docker compose command
if docker compose version &> /dev/null; then
    DOCKER_COMPOSE="docker compose"
else
    DOCKER_COMPOSE="docker compose"
fi

echo -e "${YELLOW}Using: $DOCKER_COMPOSE${NC}\n"

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo -e "${RED}❌ Docker is not installed!${NC}"
    exit 1
fi

# Stop existing containers
echo -e "${YELLOW}🛑 Stopping existing containers...${NC}"
$DOCKER_COMPOSE down

# Remove old images (optional - uncomment if needed)
# $DOCKER_COMPOSE down --rmi all

# Set proper permissions BEFORE building
echo -e "${YELLOW}🔧 Setting permissions...${NC}"
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chown -R $USER:$USER storage bootstrap/cache 2>/dev/null || true

# Create necessary directories
mkdir -p storage/app/public
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Build and start containers
echo -e "${YELLOW}🏗️  Building Docker image...${NC}"
$DOCKER_COMPOSE build --no-cache

echo -e "${YELLOW}🚀 Starting containers...${NC}"
$DOCKER_COMPOSE up -d

# Wait for container to be healthy
echo -e "${YELLOW}⏳ Waiting for container to be ready...${NC}"
sleep 10

# Check container status
if ! docker ps | grep -q stream-xtube-app; then
    echo -e "${RED}❌ Container failed to start!${NC}"
    echo -e "${YELLOW}Showing logs:${NC}"
    $DOCKER_COMPOSE logs --tail=50
    exit 1
fi

# Run migrations
echo -e "${YELLOW}📊 Running database migrations...${NC}"
$DOCKER_COMPOSE exec -T app php artisan migrate --force

# Create storage link
echo -e "${YELLOW}🔗 Creating storage link...${NC}"
$DOCKER_COMPOSE exec -T app php artisan storage:link

# Clear and cache config
echo -e "${YELLOW}🗑️  Clearing caches...${NC}"
$DOCKER_COMPOSE exec -T app php artisan config:clear
$DOCKER_COMPOSE exec -T app php artisan cache:clear
$DOCKER_COMPOSE exec -T app php artisan view:clear
$DOCKER_COMPOSE exec -T app php artisan route:clear

echo -e "${YELLOW}💾 Caching configuration...${NC}"
$DOCKER_COMPOSE exec -T app php artisan config:cache
$DOCKER_COMPOSE exec -T app php artisan route:cache
$DOCKER_COMPOSE exec -T app php artisan view:cache

# Set final permissions
echo -e "${YELLOW}🔒 Setting final permissions...${NC}"
$DOCKER_COMPOSE exec -T app chown -R www-data:www-data /app/storage /app/bootstrap/cache
$DOCKER_COMPOSE exec -T app chmod -R 775 /app/storage /app/bootstrap/cache

# Test application
echo -e "${YELLOW}🧪 Testing application...${NC}"
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/health.php)
if [ "$HTTP_CODE" = "200" ]; then
    echo -e "${GREEN}✅ Application is running successfully!${NC}"
else
    echo -e "${RED}❌ Application health check failed (HTTP $HTTP_CODE)${NC}"
    echo -e "${YELLOW}Showing logs:${NC}"
    $DOCKER_COMPOSE logs --tail=30
fi

# Show container status
echo -e "\n${GREEN}========================================${NC}"
echo -e "${GREEN}  Container Status${NC}"
echo -e "${GREEN}========================================${NC}"
$DOCKER_COMPOSE ps

# Show logs
echo -e "\n${YELLOW}📋 Recent logs:${NC}"
$DOCKER_COMPOSE logs --tail=20

echo -e "\n${GREEN}========================================${NC}"
echo -e "${GREEN}  Deployment Complete!${NC}"
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✅ Application URL: https://stream.xtubes.site${NC}"
echo -e "${GREEN}✅ Local test: http://localhost:8000${NC}"
echo -e "\n${YELLOW}Useful commands:${NC}"
echo -e "  View logs:        $DOCKER_COMPOSE logs -f"
echo -e "  Enter container:  $DOCKER_COMPOSE exec app bash"
echo -e "  Restart:          $DOCKER_COMPOSE restart"
echo -e "  Stop:             $DOCKER_COMPOSE down"

# Remove old images (optional - uncomment if needed)
# docker compose down --rmi all

# Set proper permissions BEFORE building
echo -e "${YELLOW}🔧 Setting permissions...${NC}"
chmod -R 775 storage bootstrap/cache
chown -R $USER:$USER storage bootstrap/cache

# Create necessary directories
mkdir -p storage/app/public
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Build and start containers
echo -e "${YELLOW}🏗️  Building Docker image...${NC}"
docker compose build --no-cache

echo -e "${YELLOW}🚀 Starting containers...${NC}"
docker compose up -d

# Wait for container to be healthy
echo -e "${YELLOW}⏳ Waiting for container to be ready...${NC}"
sleep 10

# Check container status
if ! docker ps | grep -q stream-xtube-app; then
    echo -e "${RED}❌ Container failed to start!${NC}"
    echo -e "${YELLOW}Showing logs:${NC}"
    docker compose logs --tail=50
    exit 1
fi

# Run migrations
echo -e "${YELLOW}📊 Running database migrations...${NC}"
docker compose exec -T app php artisan migrate --force

# Create storage link
echo -e "${YELLOW}🔗 Creating storage link...${NC}"
docker compose exec -T app php artisan storage:link

# Clear and cache config
echo -e "${YELLOW}🗑️  Clearing caches...${NC}"
docker compose exec -T app php artisan config:clear
docker compose exec -T app php artisan cache:clear
docker compose exec -T app php artisan view:clear
docker compose exec -T app php artisan route:clear

echo -e "${YELLOW}💾 Caching configuration...${NC}"
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache

# Set final permissions
echo -e "${YELLOW}🔒 Setting final permissions...${NC}"
docker compose exec -T app chown -R www-data:www-data /app/storage /app/bootstrap/cache
docker compose exec -T app chmod -R 775 /app/storage /app/bootstrap/cache

# Test application
echo -e "${YELLOW}🧪 Testing application...${NC}"
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/health.php)
if [ "$HTTP_CODE" = "200" ]; then
    echo -e "${GREEN}✅ Application is running successfully!${NC}"
else
    echo -e "${RED}❌ Application health check failed (HTTP $HTTP_CODE)${NC}"
    echo -e "${YELLOW}Showing logs:${NC}"
    docker compose logs --tail=30
fi

# Show container status
echo -e "\n${GREEN}========================================${NC}"
echo -e "${GREEN}  Container Status${NC}"
echo -e "${GREEN}========================================${NC}"
docker compose ps

# Show logs
echo -e "\n${YELLOW}📋 Recent logs:${NC}"
docker compose logs --tail=20

echo -e "\n${GREEN}========================================${NC}"
echo -e "${GREEN}  Deployment Complete!${NC}"
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✅ Application URL: https://stream.xtubes.site${NC}"
echo -e "${GREEN}✅ Local test: http://localhost:8000${NC}"
echo -e "\n${YELLOW}Useful commands:${NC}"
echo -e "  View logs:        docker compose logs -f"
echo -e "  Enter container:  docker compose exec app bash"
echo -e "  Restart:          docker compose restart"
echo -e "  Stop:             docker compose down"
