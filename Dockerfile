FROM dunglas/frankenphp:php8.2-alpine

WORKDIR /app

RUN install-php-extensions \
    opcache \
    gd \
    intl \
    pdo_pgsql \
    pgsql \
    zip \
    pcntl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY --chown=www-data:www-data composer.json composer.lock ./

RUN composer install --no-dev --no-interaction --no-scripts --no-progress

COPY --chown=www-data:www-data . .

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R ug+rwx /app/storage /app/bootstrap/cache

RUN composer dump-autoload --no-interaction --no-dev --classmap-authoritative && \
    php artisan package:discover --ansi && \
    php artisan optimize

ENTRYPOINT ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=80"]

EXPOSE 80
EXPOSE 443

LABEL org.opencontainers.image.source="https://github.com/dunglas/frankenphp"