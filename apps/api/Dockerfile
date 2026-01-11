FROM ghcr.io/roadrunner-server/roadrunner:2025.1.6 AS rrbin

FROM php:8.3-cli-alpine

# Dependencies + extensions
RUN apk add --no-cache \
    bash git unzip libpq-dev openssh libxml2-dev curl tar gzip ca-certificates \
    && docker-php-ext-install pdo pdo_pgsql xml

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy RR binary from stage
COPY --from=rrbin /usr/bin/rr /usr/local/bin/rr

WORKDIR /var/www/api
COPY . .

# Install PHP deps (tanpa dev di image)
RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader || true

EXPOSE 8000
CMD ["php", "artisan", "octane:start", "--server=roadrunner", "--host=0.0.0.0", "--port=8000"]
