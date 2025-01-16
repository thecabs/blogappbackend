FROM php:8.1-fpm

# Installer les outils et extensions nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de l'application
COPY . .

# Installer Composer et les dépendances PHP
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Configurer les permissions pour le stockage et les logs Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer le port de l'application
EXPOSE 8000

# Démarrer l'application Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000
