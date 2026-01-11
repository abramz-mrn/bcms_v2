FROM php:8.3-cli-alpine

# Dependencies + extensions
RUN apk add --no-cache bash git unzip libpq-dev openssh libxml2-dev curl tar gzip \
    && docker-php-ext-install pdo pdo_pgsql xml

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# RoadRunner binary (use a known tag)
ENV RR_VERSION=v2024.2.0
RUN curl -fL https://github.com/roadrunner-server/roadrunner/releases/download/${RR_VERSION}/roadrunner-${RR_VERSION}-linux-amd64.tar.gz \
  | tar -xz -C /usr/local/bin rr

WORKDIR /var/www/api
COPY . .

RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader || true

EXPOSE 8000
CMD ["php", "artisan", "octane:start", "--server=roadrunner", "--host=0.0.0.0", "--port=8000"]
