# Image officielle FrankenPHP, PHP 8.4 complète
FROM dunglas/frankenphp:1.9.1-php8.4.13

# Définir le dossier de travail
WORKDIR /var/www/html

# Installer extensions PHP nécessaires pour Laravel + Octane + PostgreSQL + Redis
RUN install-php-extensions \
    pcntl \
    pdo \
    pdo_pgsql \
    intl \
    zip \
    gd \
    exif \
    bcmath \
    opcache \
    ftp \
    redis

# Copier le projet dans le container
COPY . .

# Installer les dépendances PHP (production)
RUN composer install --prefer-dist --optimize-autoloader --no-dev --no-interaction

# Créer les dossiers nécessaires et donner les droits corrects
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Exposer le port 8000 pour FrankenPHP
EXPOSE 8000

# Lancer Laravel Octane avec FrankenPHP
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]
