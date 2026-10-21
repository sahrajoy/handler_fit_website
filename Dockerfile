# Container für Render (oder jeden anderen Docker-Host): PHP 8.2 mit Apache

# Abhängigkeiten (PHPMailer) installieren
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

FROM php:8.2-apache

# Produktions-Einstellungen: keine Fehlermeldungen im Browser
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# .htaccess-Dateien erlauben (sperren lib/, db/ und vendor/) und auf den Port von Render hören
ENV PORT=10000
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf \
    && sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/' /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html/
COPY --from=vendor /app/vendor /var/www/html/vendor/

# Die SQLite-Datenbank wird in db/ angelegt
RUN chown -R www-data:www-data /var/www/html/db
