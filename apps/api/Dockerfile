FROM php:8.3-cli-alpine

# Dependencies
RUN apk add --no-cache bash git unzip libpq-dev openssh \
    && docker-php-ext-install pdo pdo_pgsql

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# RoadRunner binary
RUN curl -L https://github.com/roadrunner-server/roadrunner/releases/download/v2024.1.0/roadrunner-2024.1.0-linux-amd64.tar.gz \
  | tar -xz -C /usr/local/bin rr

WORKDIR /var/www/api
COPY . .

RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader || true

EXPOSE 8000
CMD ["php", "artisan", "octane:start", "--server=roadrunner", "--host=0.0.0.0", "--port=8000"]
