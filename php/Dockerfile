FROM php:8.2-apache

RUN docker-php-ext-install pdo_mysql

RUN a2enmod rewrite headers

# Security headers para Apache
RUN echo "ServerTokens Prod" >> /etc/apache2/apache2.conf \
    && echo "ServerSignature Off" >> /etc/apache2/apache2.conf \
    && echo "Header always set X-Frame-Options DENY" >> /etc/apache2/apache2.conf \
    && echo "Header always set X-Content-Type-Options nosniff" >> /etc/apache2/apache2.conf \
    && echo "Header always set Referrer-Policy no-referrer" >> /etc/apache2/apache2.conf

# Correr como usuario no-root
USER www-data
