# Usa la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instala las extensiones necesarias de PHP y otras herramientas
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install pdo_pgsql \
    && a2enmod rewrite

# Instala Composer (para manejar dependencias como dompdf)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia todo el contenido del proyecto al contenedor
COPY . /var/www/html/

# Configura el DocumentRoot de Apache a la carpeta donde está tu index.php
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instala las dependencias de PHP con Composer (como dompdf)
RUN composer install --no-interaction

# Expone el puerto 80, que es el estándar para Apache
EXPOSE 80
