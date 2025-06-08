# Usa immagine ufficiale PHP con Apache
FROM php:8.2-apache


# Installa estensioni PHP necessarie (modifica se servono altre)
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip \
    && docker-php-ext-install zip pdo pdo_mysql

# Abilita mod_rewrite per Laravel
RUN a2enmod rewrite

# Cambia il DocumentRoot in Apache per usare /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Modifica la configurazione del virtual host default
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Copia i file
COPY . /var/www/html

WORKDIR /var/www/html

# Installa Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installa le dipendenze di Laravel
RUN composer install --no-dev --optimize-autoloader

# Set permessi per storage & cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Espone porta 80
EXPOSE 80