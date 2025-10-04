# 1. Stop container yang error

docker compose down

# 2. Update Caddyfile (copy isi dari artifact Caddyfile yang baru)

nano Caddyfile

# 3. Paste konten ini:

{ # Global options
auto_https off
admin off

    # Order directive for FrankenPHP
    order php_server before file_server

}

:80 { # Set root directory
root \* /app/public

    # Logging
    log {
        output stdout
        format console
        level INFO
    }

    # Encode responses
    encode gzip

    # Security headers
    header {
        X-Frame-Options "SAMEORIGIN"
        X-Content-Type-Options "nosniff"
        X-XSS-Protection "1; mode=block"
        Referrer-Policy "strict-origin-when-cross-origin"
    }

    # PHP + Laravel handling
    php_server

}

# 4. Rebuild container

docker compose build --no-cache

# 5. Start container

docker compose up -d

# 6. Check logs

docker compose logs -f app

# 7. Test health (di terminal baru)

####################

## Atau lebih cepat, buat file quick-fix.sh:

curl http://localhost:8000/health.php

```bash
chmod +x QUICK_FIX.sh
./QUICK_FIX.sh
```

####################
