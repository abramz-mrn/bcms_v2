FROM php:8.3-cli-alpine

# Dependencies + extensions
RUN apk add --no-cache \
    bash git unzip libpq-dev openssh libxml2-dev curl tar gzip ca-certificates \
    && docker-php-ext-install pdo pdo_pgsql xml

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# RoadRunner binary (pinned) dengan retry dan extract terpisah
ENV RR_VERSION=v2024.1.0
RUN curl -fL --retry 5 --retry-delay 2 --retry-all-errors \
      -o /tmp/rr.tgz \
      https://github.com/roadrunner-server/roadrunner/releases/download/${RR_VERSION}/roadrunner-${RR_VERSION}-linux-amd64.tar.gz \
  && tar -xzf /tmp/rr.tgz -C /usr/local/bin rr \
  && rm /tmp/rr.tgz

WORKDIR /var/www/api
COPY . .

# Bila dependency dev tidak perlu di image, biarkan seperti ini
RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader || true

EXPOSE 8000
CMD ["php", "artisan", "octane:start", "--server=roadrunner", "--host=0.0.0.0", "--port=8000"]
