# Étape 1 : Utiliser une image PHP comme base
FROM php:8.1-fpm

# Étape 2 : Installer les outils et extensions nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    default-mysql-client \
    zlib1g-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Étape 3 : Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Vérification de l'installation de Composer (optionnelle, pour débogage)
RUN composer --version

# Étape 4 : Définir le répertoire de travail
WORKDIR /var/www/html

# Étape 5 : Copier uniquement les fichiers nécessaires pour Composer
COPY composer.json composer.lock ./

# Étape 6 : Installer les dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Étape 7 : Copier tout le projet
COPY . .

# Étape 8 : Configurer les permissions pour le stockage et les logs Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Étape 9 : Exposer le port de l'application
EXPOSE 8000

# Étape 10 : Démarrer l'application Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
