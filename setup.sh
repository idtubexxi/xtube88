#!/bin/bash

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}  Initial Setup Script${NC}"
echo -e "${BLUE}========================================${NC}\n"

# Check if running as root
# if [ "$EUID" -eq 0 ]; then
#     echo -e "${RED}❌ Please don't run this script as root!${NC}"
#     echo -e "${YELLOW}Run as normal user: ./setup.sh${NC}"
#     exit 1
# fi

# Create necessary directories
echo -e "${YELLOW}📁 Creating directories...${NC}"
mkdir -p storage/app/public
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache
mkdir -p public/storage

# Set permissions
echo -e "${YELLOW}🔧 Setting permissions...${NC}"
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod -R 755 public

# Copy environment file if not exists
if [ ! -f .env.docker ]; then
    echo -e "${YELLOW}📝 Creating .env.docker...${NC}"
    cp .env.docker.example .env.docker
    echo -e "${YELLOW}⚠️  Please edit .env.docker with your configuration!${NC}"
fi

# Create Caddyfile if not exists
if [ ! -f Caddyfile ]; then
    echo -e "${YELLOW}📝 Creating Caddyfile...${NC}"
    cat > Caddyfile << 'EOF'
{
    auto_https off
    admin off
}

:80 {
    root * /app/public
    php_server
    file_server

    log {
        output stdout
        format console
        level INFO
    }

    try_files {path} {path}/ /index.php?{query}
    encode gzip

    header {
        X-Frame-Options "SAMEORIGIN"
        X-Content-Type-Options "nosniff"
        X-XSS-Protection "1; mode=block"
        Referrer-Policy "strict-origin-when-cross-origin"
    }
}
EOF
    echo -e "${GREEN}✅ Caddyfile created${NC}"
fi

# Make deploy script executable
if [ -f deploy.sh ]; then
    chmod +x deploy.sh
    echo -e "${GREEN}✅ deploy.sh is now executable${NC}"
fi

# Check Docker installation
echo -e "\n${YELLOW}🐳 Checking Docker installation...${NC}"
if ! command -v docker &> /dev/null; then
    echo -e "${RED}❌ Docker is not installed${NC}"
    echo -e "${YELLOW}Install Docker: https://docs.docker.com/engine/install/ubuntu/${NC}"
    exit 1
else
    DOCKER_VERSION=$(docker --version)
    echo -e "${GREEN}✅ Docker installed: $DOCKER_VERSION${NC}"
fi

# Check Docker Compose installation
if ! command -v docker-compose &> /dev/null && ! docker compose version &> /dev/null; then
    echo -e "${RED}❌ Docker Compose is not installed${NC}"
    echo -e "${YELLOW}Install Docker Compose: https://docs.docker.com/compose/install/${NC}"
    exit 1
else
    if command -v docker-compose &> /dev/null; then
        COMPOSE_VERSION=$(docker-compose --version)
    else
        COMPOSE_VERSION=$(docker compose version)
    fi
    echo -e "${GREEN}✅ Docker Compose installed: $COMPOSE_VERSION${NC}"
fi

# Check if user is in docker group
if groups | grep -q docker; then
    echo -e "${GREEN}✅ User is in docker group${NC}"
else
    echo -e "${YELLOW}⚠️  User is not in docker group${NC}"
    echo -e "${YELLOW}Run: sudo usermod -aG docker $USER${NC}"
    echo -e "${YELLOW}Then logout and login again${NC}"
fi

# Test database connection
echo -e "\n${YELLOW}🔍 Testing database connection...${NC}"
if command -v psql &> /dev/null; then
    if psql "postgresql://f2371dc5983395c54215451b45f0b9b09117cc2abfd6367376831729c17f99af:sk_t7bcapvo544KXIlBAQRCI@db.prisma.io:5432/postgres?sslmode=require" -c "SELECT 1" &> /dev/null; then
        echo -e "${GREEN}✅ Database connection successful${NC}"
    else
        echo -e "${RED}❌ Cannot connect to database${NC}"
        echo -e "${YELLOW}Please check your database credentials in .env.docker${NC}"
    fi
else
    echo -e "${YELLOW}⚠️  psql not installed, skipping database test${NC}"
fi

# Check Nginx configuration
echo -e "\n${YELLOW}🌐 Checking Nginx configuration...${NC}"
if [ -f /etc/nginx/sites-available/stream.xtubes.site ]; then
    echo -e "${GREEN}✅ Nginx config found${NC}"
    if nginx -t &> /dev/null; then
        echo -e "${GREEN}✅ Nginx config is valid${NC}"
    else
        echo -e "${RED}❌ Nginx config has errors${NC}"
        echo -e "${YELLOW}Run: sudo nginx -t${NC}"
    fi
else
    echo -e "${YELLOW}⚠️  Nginx config not found at /etc/nginx/sites-available/stream.xtubes.site${NC}"
    echo -e "${YELLOW}Please copy domain.conf to /etc/nginx/sites-available/stream.xtubes.site${NC}"
fi

# Check SSL certificates
echo -e "\n${YELLOW}🔒 Checking SSL certificates...${NC}"
if [ -f /etc/letsencrypt/live/stream.xtubes.site/fullchain.pem ]; then
    echo -e "${GREEN}✅ SSL certificate found${NC}"
    CERT_EXPIRY=$(openssl x509 -enddate -noout -in /etc/letsencrypt/live/stream.xtubes.site/fullchain.pem 2>/dev/null | cut -d= -f2)
    echo -e "${GREEN}   Expires: $CERT_EXPIRY${NC}"
else
    echo -e "${YELLOW}⚠️  SSL certificate not found${NC}"
    echo -e "${YELLOW}Run: sudo certbot --nginx -d stream.xtubes.site${NC}"
fi

# Summary
echo -e "\n${BLUE}========================================${NC}"
echo -e "${BLUE}  Setup Summary${NC}"
echo -e "${BLUE}========================================${NC}"
echo -e "${GREEN}✅ Directories created${NC}"
echo -e "${GREEN}✅ Permissions set${NC}"
echo -e "${GREEN}✅ Configuration files ready${NC}"

echo -e "\n${YELLOW}📋 Next Steps:${NC}"
echo -e "  1. Edit .env.docker with your configuration"
echo -e "  2. Make sure Nginx is configured: sudo cp domain.conf /etc/nginx/sites-available/stream.xtubes.site"
echo -e "  3. Enable Nginx site: sudo ln -s /etc/nginx/sites-available/stream.xtubes.site /etc/nginx/sites-enabled/"
echo -e "  4. Test Nginx: sudo nginx -t"
echo -e "  5. Reload Nginx: sudo systemctl reload nginx"
echo -e "  6. Run deployment: ./deploy.sh"

echo -e "\n${GREEN}🚀 Ready to deploy!${NC}\n"
