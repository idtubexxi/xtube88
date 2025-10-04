#!/bin/bash

echo "🔧 Quick Fix for Caddyfile Error"
echo "================================"

# Stop container
echo "1. Stopping container..."
docker compose down

# Update Caddyfile
echo "2. Creating fixed Caddyfile..."
cat > Caddyfile << 'EOF'
{
    # Global options
    auto_https off
    admin off

    # Order directive for FrankenPHP
    order php_server before file_server
}

:80 {
    # Set root directory
    root * /app/public

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
EOF

echo "✅ Caddyfile updated!"

# Rebuild container
echo "3. Rebuilding container..."
docker compose build --no-cache

# Start container
echo "4. Starting container..."
docker compose up -d

# Wait a bit
echo "5. Waiting for container to start..."
sleep 15

# Check status
echo "6. Checking status..."
docker ps | grep stream-xtube-app

# Test health
echo "7. Testing health..."
curl -s -o /dev/null -w "HTTP Status: %{http_code}\n" http://localhost:8000/health.php

echo ""
echo "✅ Fix applied! Check logs with: docker compose logs -f app"
