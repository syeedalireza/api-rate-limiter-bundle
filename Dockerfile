FROM php:8.3-fpm-alpine AS base

RUN apk add --no-cache $PHPIZE_DEPS

RUN pecl install redis && docker-php-ext-enable redis

RUN docker-php-ext-install opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

FROM base AS development

RUN pecl install xdebug && docker-php-ext-enable xdebug

USER www-data

CMD ["php-fpm"]

FROM base AS production

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --chown=www-data:www-data . /app
RUN composer install --no-dev --optimize-autoloader

USER www-data

CMD ["php-fpm"]
