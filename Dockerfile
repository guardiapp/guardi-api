# Laravel 11 API — Render (Docker)
# Variables obligatorias en Render: APP_KEY, APP_URL, DB_*, JWT_SECRET,
# MYSQL_ATTR_SSL_CA=database/certs/ca.pem, MYSQL_ATTR_SSL_VERIFY_SERVER_CERT=true

FROM php:8.2-cli-bookworm

RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libzip-dev libpng-dev libonig-dev libxml2-dev libicu-dev \
    libfreetype6-dev libjpeg62-turbo-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql mbstring zip exif pcntl bcmath gd intl opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction

COPY package.json package-lock.json ./
RUN npm ci

COPY . .

RUN mkdir -p database/certs \
    && cp docker/aiven-ca.pem database/certs/ca.pem

RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && npm run build \
    && mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache/data bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

EXPOSE 10000

# Render inyecta PORT en tiempo de ejecución
CMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
