# 🚀 Deployment Guide - Laravel + Docker + FrankenPHP

## 📋 Prerequisites

- Ubuntu 24.04 LTS
- Docker & Docker Compose installed
- Domain pointed to server IP
- SSL certificate configured (Let's Encrypt)
- PostgreSQL database (Prisma.io)

## 🔧 Initial Setup

### 1. Install Docker & Docker Compose

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Add user to docker group
sudo usermod -aG docker $USER

# Install Docker Compose (if not included)
sudo apt install docker compose-plugin -y

# Verify installation
docker --version
docker compose version
```

### 2. Clone Repository

```bash
cd /var/www
git clone your-repo-url stream-xtube
cd stream-xtube
```

### 3. Run Setup Script

```bash
# Make setup script executable
chmod +x setup.sh

# Run setup
./setup.sh
```

### 4. Configure Environment

```bash
# Edit .env.docker
nano .env.docker

# Key settings to verify:
# - APP_URL=https://stream.xtubes.site
# - DB_* credentials
# - APP_KEY (must be set)
```

### 5. Setup Nginx

```bash
# Copy Nginx configuration
sudo cp domain.conf /etc/nginx/sites-available/stream.xtubes.site

# Enable site
sudo ln -s /etc/nginx/sites-available/stream.xtubes.site /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

### 6. Setup SSL (if not already done)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Get SSL certificate
sudo certbot --nginx -d stream.xtubes.site

# Test auto-renewal
sudo certbot renew --dry-run
```

## 🚢 Deployment Steps

### Option 1: Quick Deploy (Recommended)

```bash
# Make deploy script executable
chmod +x deploy.sh

# Run deployment
./deploy.sh
```

### Option 2: Manual Deployment

```bash
# 1. Stop existing containers
docker compose down

# 2. Build image
docker compose build --no-cache

# 3. Start containers
docker compose up -d

# 4. Run migrations
docker compose exec app php artisan migrate --force

# 5. Create storage link
docker compose exec app php artisan storage:link

# 6. Cache configuration
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache

# 7. Set permissions
docker compose exec app chown -R www-data:www-data /app/storage /app/bootstrap/cache
docker compose exec app chmod -R 775 /app/storage /app/bootstrap/cache
```

## ✅ Verification

### 1. Check Container Status

```bash
docker ps
docker compose ps
```

### 2. Check Logs

```bash
docker compose logs -f app
```

### 3. Test Endpoints

```bash
# Health check
curl http://localhost:8000/health.php

# Homepage
curl http://localhost:8000

# Via domain
curl https://stream.xtubes.site
```

### 4. Check Database Connection

```bash
docker compose exec app php artisan db:show
```

### 5. Check Storage Link

```bash
docker compose exec app ls -la /app/public/storage
```

## 🔄 Updates & Maintenance

### Update Application

```bash
# Pull latest changes
git pull origin main

# Rebuild and deploy
./deploy.sh
```

### View Logs

```bash
# Application logs
docker compose logs -f app

# Last 100 lines
docker compose logs --tail=100 app

# Nginx logs
sudo tail -f /var/log/nginx/xtube-access.log
sudo tail -f /var/log/nginx/xtube-error.log
```

### Restart Services

```bash
# Restart Docker container
docker compose restart app

# Restart Nginx
sudo systemctl restart nginx

# Full restart
docker compose down
docker compose up -d
```

### Clear Cache

```bash
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear
docker compose exec app php artisan route:clear
```

### Database Management

```bash
# Run migrations
docker compose exec app php artisan migrate

# Rollback
docker compose exec app php artisan migrate:rollback

# Fresh migration (⚠️ Deletes all data!)
docker compose exec app php artisan migrate:fresh

# Seed database
docker compose exec app php artisan db:seed
```

## 🛠️ Common Tasks

### Enter Container Shell

```bash
docker compose exec app bash
```

### Run Artisan Commands

```bash
docker compose exec app php artisan [command]
```

### Run Composer

```bash
docker compose exec app composer install
docker compose exec app composer update
```

### Run Tinker

```bash
docker compose exec app php artisan tinker
```

### Check PHP Info

```bash
docker compose exec app php -i
```

## 📊 Monitoring

### Resource Usage

```bash
# Container stats
docker stats stream-xtube-app

# Disk usage
docker system df
df -h
```

### Application Health

```bash
# Check if app is responding
watch -n 5 'curl -s -o /dev/null -w "%{http_code}" http://localhost:8000'

# Monitor logs
docker compose logs -f app | grep -i error
```

## 🔒 Security

### Update Permissions

```bash
# On host
chmod -R 775 storage bootstrap/cache
chown -R $USER:$USER storage bootstrap/cache

# In container
docker compose exec app chown -R www-data:www-data /app/storage /app/bootstrap/cache
```

### Firewall Configuration

```bash
# Allow HTTP/HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Deny direct access to Docker port (optional)
sudo ufw deny 8000/tcp
```

### SSL Renewal

```bash
# Manual renewal
sudo certbot renew

# Test renewal
sudo certbot renew --dry-run
```

## 🆘 Emergency Procedures

### Complete Reset (⚠️ Use with caution!)

```bash
# Stop containers
docker compose down -v

# Remove all data
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*

# Rebuild
docker compose build --no-cache
docker compose up -d

# Recreate database
docker compose exec app php artisan migrate:fresh --seed
```

### Rollback to Previous Version

```bash
# Stop containers
docker compose down

# Checkout previous version
git checkout [previous-commit-hash]

# Redeploy
./deploy.sh
```

## 📈 Performance Optimization

### Enable Caching

```bash
docker compose exec app php artisan optimize
```

### Queue Workers (if using queues)

```bash
# Start queue worker
docker compose exec app php artisan queue:work --daemon
```

### Schedule Tasks

```bash
# Add to host crontab
* * * * * docker compose -f /var/www/stream-xtube/docker compose.yml exec -T app php artisan schedule:run >> /dev/null 2>&1
```

## 📞 Support

If you encounter issues:

1. Check logs: `docker compose logs -f app`
2. Verify configuration: `docker compose exec app php artisan config:show`
3. Test database: `docker compose exec app php artisan db:show`
4. Review troubleshooting guide: `TROUBLESHOOTING.md`

## 🔗 Useful Links

- **Application**: https://stream.xtubes.site
- **Health Check**: https://stream.xtubes.site/health.php
- **Local Test**: http://localhost:8000

## 📝 Notes

- Always test in staging environment first
- Backup database before major updates
- Monitor logs after deployment
- Keep SSL certificates up to date
- Regular security updates: `sudo apt update && sudo apt upgrade`
