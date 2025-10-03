FROM dunglas/frankenphp:latest

# Install tools
RUN apt-get update && apt-get install -y \
    curl \
    procps \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN install-php-extensions \
    pdo_mysql \
    pdo_pgsql \
    bcmath \
    gd \
    intl \
    zip \
    opcache \
    redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files first
COPY composer.json composer.lock ./

# Copy safe environment file untuk build
COPY .env.docker .env

# Install dependencies WITHOUT running scripts that need database
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy application
COPY . .

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

# Run ONLY safe artisan commands
RUN php artisan package:discover --no-interaction --quiet

# Create simple test file
RUN echo "<?php echo 'OK'; ?>" > /app/public/health.php

EXPOSE 80

CMD ["frankenphp", "run"]
