FROM ghcr.io/roadrunner-server/roadrunner:2025.1.6 AS rrbin

FROM php:8.3-cli-alpine

# Runtime deps
RUN apk add --no-cache \
    bash git unzip libpq-dev openssh libxml2-dev curl tar gzip ca-certificates

# Build deps + ekstensi
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && docker-php-ext-install pdo pdo_pgsql xml pcntl \
    && apk del .build-deps

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# RR binary
COPY --from=rrbin /usr/bin/rr /usr/local/bin/rr

WORKDIR /var/www/api
COPY . .

RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader || true

EXPOSE 8000
CMD ["php", "artisan", "octane:start", "--server=roadrunner", "--host=0.0.0.0", "--port=8000"]
