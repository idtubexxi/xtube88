FROM dunglas/frankenphp:latest

# Install PHP extensions yang dibutuhkan Laravel
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

# Set working directory
WORKDIR /app

# Copy composer files first (for better caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy application files
COPY . .
COPY --chown=www-data:www-data . /app

# Run composer scripts
RUN composer run-script post-install-cmd

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

# Generate key (tapi better di environment variable)
# RUN php artisan key:generate --force

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=30s --retries=3 \
    CMD curl -f http://localhost:80/up || exit 1

EXPOSE 80

CMD ["frankenphp", "run"]
