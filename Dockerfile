FROM node:20-alpine AS node_build
WORKDIR /app
COPY package.json package-lock.json vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm ci --no-audit --no-fund
RUN npm run build

FROM composer:2 AS composer_build
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-progress --prefer-dist --optimize-autoloader --no-scripts

FROM php:8.2-apache
WORKDIR /var/www/html

RUN apt-get update \
  && apt-get install -y --no-install-recommends libzip-dev unzip \
  && docker-php-ext-install zip pdo_mysql opcache \
  && a2enmod rewrite \
  && rm -rf /var/lib/apt/lists/*

RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
  && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

COPY --from=composer_build /app/vendor ./vendor

COPY app ./app
COPY bootstrap ./bootstrap
COPY config ./config
COPY database ./database
COPY public ./public
COPY resources ./resources
COPY routes ./routes
COPY storage ./storage
COPY artisan ./artisan

COPY --from=node_build /app/public/build ./public/build

RUN mkdir -p bootstrap/cache storage/framework/cache storage/framework/sessions storage/framework/views storage/logs \
  && chown -R www-data:www-data storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

