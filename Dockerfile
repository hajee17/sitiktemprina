# Dockerfile

# Gunakan base image resmi FrankenPHP untuk Laravel
FROM dunglas/frankenphp:php8.2-alpine   

# (Opsional) Instal ekstensi PHP tambahan jika diperlukan
RUN install-php-extensions pgsql pdo_pgsql intl zip
# Baris di atas sudah mencakup pgsql yang Anda butuhkan. Hapus komentar jika perlu ekstensi lain.

# Atur variabel lingkungan untuk mode produksi
ENV APP_ENV=local
ENV FRANKENPHP_CONFIG="worker /app/public/index.php"

# Salin composer.json dan composer.lock terlebih dahulu untuk caching dependensi
COPY --chown=frankenphp:frankenphp composer.json composer.lock ./

# Instal dependensi composer
RUN composer install --no-dev --no-interaction --no-scripts --no-progress

# Salin sisa file aplikasi
COPY --chown=frankenphp:frankenphp . .

# Jalankan perintah post-install composer
RUN composer dump-autoload --no-interaction --no-dev --classmap-authoritative && \
    php artisan package:discover --ansi && \
    php artisan storage:link && \
    php artisan optimize

# Set header keamanan
LABEL org.opencontainers.image.source="https://github.com/Dunglas/frankenphp"
