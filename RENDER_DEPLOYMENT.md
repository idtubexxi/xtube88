# 🚀 Deploy Laravel ke Render.com

## 📋 Persiapan

### 1. File yang Diperlukan

Pastikan file-file ini ada di root project:

- ✅ `render.yaml` - Blueprint konfigurasi
- ✅ `Dockerfile.render` - Docker image untuk Render
- ✅ `nginx.render.conf` - Nginx config
- ✅ `supervisord.render.conf` - Supervisor config
- ✅ `docker-entrypoint.sh` - Startup script

### 2. Push ke GitHub

```bash
# Initialize git (jika belum)
git init
git add .
git commit -m "Initial commit for Render deployment"

# Push ke GitHub
git remote add origin https://github.com/username/stream-xtube.git
git branch -M main
git push -u origin main
```

## 🌐 Deploy ke Render

### Step 1: Buat Akun Render

1. Buka https://render.com
2. Sign up dengan GitHub
3. Authorize Render untuk akses repo

### Step 2: Deploy dari Dashboard

**Via Blueprint (Recommended):**

1. Klik **New** → **Blueprint**
2. Connect repository GitHub Anda
3. Render akan otomatis detect `render.yaml`
4. Klik **Apply**
5. Tunggu deployment selesai (~5-10 menit)

**Manual (Alternative):**

1. **Create PostgreSQL Database:**

   - New → PostgreSQL
   - Name: `stream-xtube-db`
   - Plan: Starter (Free)
   - Create

2. **Create Redis:**

   - New → Redis
   - Name: `stream-xtube-redis`
   - Plan: Starter (Free)
   - Create

3. **Create Web Service:**

   - New → Web Service
   - Connect GitHub repo
   - Name: `stream-xtube`
   - Environment: `Docker`
   - Dockerfile Path: `Dockerfile.render`
   - Plan: Starter ($7/month) atau Free
   - Add Disk:
     - Name: `storage`
     - Mount Path: `/app/storage`
     - Size: 10GB

4. **Set Environment Variables:**

   ```
   APP_NAME=XTube
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=(generate nanti)
   APP_URL=https://stream-xtube.onrender.com

   DB_CONNECTION=pgsql
   DB_HOST=(dari PostgreSQL internal connection)
   DB_PORT=5432
   DB_DATABASE=stream_xtube
   DB_USERNAME=(dari PostgreSQL)
   DB_PASSWORD=(dari PostgreSQL)

   CACHE_STORE=redis
   SESSION_DRIVER=redis
   QUEUE_CONNECTION=redis
   REDIS_HOST=(dari Redis internal connection)
   REDIS_PORT=6379
   ```

5. **Deploy!** - Klik "Create Web Service"

### Step 3: Generate APP_KEY

Setelah pertama kali deploy:

1. Buka **Shell** di Render dashboard
2. Jalankan:
   ```bash
   php artisan key:generate --show
   ```
3. Copy key yang muncul
4. Tambahkan ke Environment Variables:
   ```
   APP_KEY=base64:xxxxxxxxxxxxx
   ```
5. Re-deploy

### Step 4: Custom Domain (Optional)

1. Buka service settings
2. Ke tab **Custom Domains**
3. Add domain: `stream.xtubes.site`
4. Update DNS:
   ```
   Type: CNAME
   Name: stream
   Value: stream-xtube.onrender.com
   ```
5. Enable "Automatic TLS"

## ⚡ Optimasi Performance

### 1. Enable Persistent Disk (Storage Link)

Render sudah support persistent disk di config `render.yaml`:

```yaml
disk:
  name: storage
  mountPath: /app/storage
  sizeGB: 10
```

Storage link otomatis dibuat saat startup!

### 2. Auto-Scaling (Paid Plan)

Upgrade ke **Standard** plan ($25/month):

- Auto-scaling berdasarkan traffic
- Zero downtime deploys
- Better CPU/RAM

### 3. CDN untuk Assets

Gunakan Cloudflare sebagai CDN:

1. Add site di Cloudflare
2. Update nameservers
3. Enable "Auto Minify" untuk CSS/JS
4. Enable "Brotli" compression

## 🔍 Monitoring & Logs

### View Logs

```bash
# Via dashboard
Dashboard → Service → Logs

# Via CLI (install Render CLI)
render logs -f
```

### Health Check

Render otomatis monitor `/up` endpoint:

- ✅ Healthy: Service running
- ❌ Unhealthy: Auto restart

### Metrics

Dashboard → Metrics akan show:

- Request per second
- Response time
- Memory usage
- CPU usage

## 🛠️ Troubleshooting

### Issue: Build Failed

**Check build logs:**

1. Dashboard → Events → Build Logs
2. Cari error message
3. Fix dan push lagi

**Common fixes:**

```bash
# Clear Composer cache
composer clear-cache

# Update dependencies
composer update --lock
```

### Issue: Migration Failed

**Run manual migration:**

1. Dashboard → Shell
2. Run:
   ```bash
   php artisan migrate --force
   ```

### Issue: 502 Bad Gateway

**Check:**

1. PHP-FPM running: `ps aux | grep php-fpm`
2. Nginx config: `nginx -t`
3. Logs: Check error logs

**Fix:**

```bash
supervisorctl restart all
```

### Issue: Slow Performance

**Optimize:**

1. **Enable OPcache** (sudah di Dockerfile)
2. **Use Redis for cache** (sudah di config)
3. **Optimize database queries:**
   ```bash
   php artisan optimize
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## 📊 Comparison: Render vs Railway

| Feature           | Render          | Railway         |
| ----------------- | --------------- | --------------- |
| **Storage Link**  | ✅ Via Disk     | ❌ Limited      |
| **Performance**   | ⚡ Fast         | ⚡ Very Fast    |
| **Price**         | $7/month        | $5/month        |
| **Database**      | Free PostgreSQL | Free PostgreSQL |
| **SSL**           | Auto (Free)     | Auto (Free)     |
| **Scaling**       | Manual/Auto     | Auto            |
| **Zero Downtime** | ✅              | ✅              |
| **Custom Domain** | ✅              | ✅              |

## 💰 Pricing

**Free Tier:**

- PostgreSQL: 256MB RAM
- Redis: 25MB
- Web Service: 750 hours/month

**Starter ($7/month):**

- Web Service: 512MB RAM
- Persistent Disk included
- Better performance

**Standard ($25/month):**

- 2GB RAM
- Auto-scaling
- Priority support

## 🚀 Deploy Checklist

- [ ] File `render.yaml` ada di root
- [ ] File `Dockerfile.render` ada di root
- [ ] File config lainnya sudah ada
- [ ] Push ke GitHub
- [ ] Connect Render ke GitHub
- [ ] Deploy via Blueprint
- [ ] Generate APP_KEY
- [ ] Test storage link: upload image
- [ ] Test database: run migrations
- [ ] Setup custom domain (optional)
- [ ] Enable monitoring

## 📞 Support

- Docs: https://render.com/docs
- Community: https://community.render.com
- Status: https://status.render.com

## 🎉 Selesai!

Website Anda sekarang live di:

- Default: `https://stream-xtube.onrender.com`
- Custom: `https://stream.xtubes.site` (jika sudah setup)

**Performance sama seperti Railway, tapi dengan storage link support!** ⚡
