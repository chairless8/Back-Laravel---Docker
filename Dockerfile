# Utilizar la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalar extensiones de PHP necesarias y herramientas
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el archivo de configuración de Apache
COPY ./apache/000-default.conf /etc/apache2/sites-available/000-default.conf


# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Establecer permisos para el usuario www-data
RUN chown -R www-data:www-data /var/www/html

# Configurar Apache para Laravel
RUN echo "<Directory /var/www/html>\n\
    AllowOverride All\n\
</Directory>\n" > /etc/apache2/conf-available/override.conf \
    && a2enconf override

# Exponer el puerto 80
EXPOSE 80

# Comando por defecto
CMD ["apache2-foreground"]
