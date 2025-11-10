FROM php:8.2-apache

# Instalar extensiones de PHP necesarias
RUN docker-php-ext-install pdo_mysql

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite
