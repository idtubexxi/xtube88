# 🔧 Troubleshooting Guide - Laravel Docker Deployment

## 📋 Quick Diagnostics

### 1. Check Container Status

```bash
docker-compose ps
docker-compose logs -f
```

### 2. Check if container is running

```bash
docker ps | grep stream-xtube-app
```

### 3. Test health endpoint

```bash
curl http://localhost:8000/health.php
curl http://localhost:8000
```

## 🚨 Common Issues & Solutions

### Issue 1: Container won't start

**Symptoms:**

- Container exits immediately
- `docker-compose ps` shows "Exit 1"

**Solutions:**

1. **Check logs:**

```bash
docker-compose logs app
```

2. **Check permissions:**

```bash
chmod -R 775 storage bootstrap/cache
chown -R $USER:$USER storage bootstrap/cache
```

3. **Rebuild without cache:**

```bash
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Issue 2: 502 Bad Gateway (Nginx)

**Symptoms:**

- Nginx returns 502 error
- Container is running but not responding

**Solutions:**

1. **Check if app is listening:**

```bash
docker-compose exec app netstat -tulpn | grep 80
```

2. **Check FrankenPHP logs:**

```bash
docker-compose logs app | tail -50
```

3. **Test direct connection:**

```bash
curl -v http://localhost:8000
```

4. **Restart container:**

```bash
docker-compose restart app
```

### Issue 3: Database Connection Failed

**Symptoms:**

- "SQLSTATE[08006] could not connect"
- Migration fails

**Solutions:**

1. **Test database connection from container:**

```bash
docker-compose exec app php artisan tinker
# Then in tinker:
DB::connection()->getPdo();
```

2. **Check database credentials:**

```bash
docker-compose exec app php -r "echo getenv('DB_HOST');"
```

3. **Test direct connection:**

```bash
psql "postgresql://f2371dc5983395c54215451b45f0b9b09117cc2abfd6367376831729c17f99af:sk_t7bcapvo544KXIlBAQRCI@db.prisma.io:5432/postgres?sslmode=require"
```

### Issue 4: Permission Denied

**Symptoms:**

- "failed to open stream: Permission denied"
- Can't write to storage/logs

**Solutions:**

1. **Fix permissions inside container:**

```bash
docker-compose exec app chown -R www-data:www-data /app/storage /app/bootstrap/cache
docker-compose exec app chmod -R 775 /app/storage /app/bootstrap/cache
```

2. **Fix permissions on host:**

```bash
sudo chown -R $USER:$USER storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

3. **Create missing directories:**

```bash
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache
```

### Issue 5: Assets not loading (CSS/JS)

**Symptoms:**

- 404 errors for CSS/JS files
- Mixed content warnings

**Solutions:**

1. **Check APP_URL and ASSET_URL:**

```bash
docker-compose exec app php artisan config:show | grep -i url
```

2. **Clear and rebuild assets:**

```bash
npm run build
docker-compose restart app
```

3. **Check Nginx is passing requests:**

```bash
curl -I https://stream.xtubes.site/build/manifest.json
```

### Issue 6: Storage link not working

**Symptoms:**

- Uploaded images not showing
- 404 on /storage/\* URLs

**Solutions:**

1. **Recreate storage link:**

```bash
docker-compose exec app rm -f /app/public/storage
docker-compose exec app php artisan storage:link
```

2. **Check if link exists:**

```bash
docker-compose exec app ls -la /app/public/storage
```

3. **Manual link creation:**

```bash
docker-compose exec app ln -s /app/storage/app/public /app/public/storage
```

## 🔍 Advanced Debugging

### Enter container shell

```bash
docker-compose exec app bash
```

### Check PHP configuration

```bash
docker-compose exec app php -i | grep -i error
docker-compose exec app php -i | grep -i memory
```

### Test database connection

```bash
docker-compose exec app php artisan db:show
```

### Check environment variables

```bash
docker-compose exec app printenv | sort
```

### Monitor logs in real-time

```bash
docker-compose logs -f app
```

### Check disk space

```bash
df -h
docker system df
```

### Clean Docker (if needed)

```bash
# Remove unused containers
docker container prune -f

# Remove unused images
docker image prune -a -f

# Remove unused volumes (CAREFUL!)
docker volume prune -f
```

## 📊 Performance Monitoring

### Check container resources

```bash
docker stats stream-xtube-app
```

### Check memory usage

```bash
docker-compose exec app free -h
```

### Check PHP-FPM status

```bash
docker-compose exec app ps aux | grep php
```

## 🆘 Emergency Fixes

### Complete Reset (⚠️ Will lose data in containers)

```bash
docker-compose down -v
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*
rm -rf storage/logs/*
docker-compose build --no-cache
docker-compose up -d
```

### Quick restart

```bash
docker-compose restart app
```

### Force rebuild and restart

```bash
docker-compose down
docker-compose build --no-cache
docker-compose up -d --force-recreate
```

## 📞 Getting Help

If issues persist:

1. **Collect information:**

```bash
# Save logs
docker-compose logs > docker-logs.txt

# Save system info
docker version > system-info.txt
docker-compose version >> system-info.txt
uname -a >> system-info.txt
```

2. **Check Laravel logs:**

```bash
docker-compose exec app tail -100 /app/storage/logs/laravel.log
```

3. **Check Nginx logs:**

```bash
tail -100 /var/log/nginx/xtube-error.log
```

## ✅ Verification Checklist

After deployment, verify:

- [ ] Container is running: `docker ps`
- [ ] Health check passes: `curl http://localhost:8000/health.php`
- [ ] Homepage loads: `curl http://localhost:8000`
- [ ] Nginx proxy works: `curl https://stream.xtubes.site`
- [ ] Database connection works: `docker-compose exec app php artisan db:show`
- [ ] Storage link exists: `docker-compose exec app ls -la public/storage`
- [ ] Permissions are correct: `docker-compose exec app ls -la storage`
- [ ] No errors in logs: `docker-compose logs app | grep -i error`
