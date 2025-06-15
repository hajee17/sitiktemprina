# Dockerfile

# Menggunakan tag base yang paling up-to-date dan stabil untuk PHP 8.2
FROM dunglas/frankenphp:php8.2-alpine

# Mengatur direktori kerja di dalam container
WORKDIR /app

# Menginstal dependensi sistem dan ekstensi PHP yang diperlukan
RUN install-php-extensions \
    opcache \
    gd \
    intl \
    pdo_pgsql \
    pgsql \
    zip

# Salin Composer binary dari image resmi Composer ke dalam image kita
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Salin composer.json dan composer.lock terlebih dahulu untuk caching
COPY --chown=frankenphp:frankenphp composer.json composer.lock ./

# Sekarang perintah ini akan berhasil karena 'composer' sudah ada
RUN composer install --no-dev --no-interaction --no-scripts --no-progress

# Salin sisa file aplikasi
COPY --chown=frankenphp:frankenphp . .

# Jalankan perintah optimasi Laravel
RUN composer dump-autoload --no-interaction --no-dev --classmap-authoritative && \
    php artisan package:discover --ansi && \
    php artisan storage:link && \
    php artisan optimize

# Set header keamanan (opsional)
LABEL org.opencontainers.image.source="https://github.com/dunglas/frankenphp"
