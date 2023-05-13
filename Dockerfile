# Utiliza una imagen base con PHP y Apache
FROM php:8.1.10-apache

# Establece el directorio de trabajo dentro del contenedor
WORKDIR /var/www/html

# Copia los archivos de tu proyecto Laravel al contenedor
COPY . /var/www/html

# Instala las extensiones de PHP necesarias
RUN docker-php-ext-install \
    gd \
    zip \
    openssl \
    pdo \
    pdo_mysql \
    fileinfo \
    exif \
    bcmath \
    ctype \
    json \
    mbstring

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instala las dependencias de tu proyecto Laravel
RUN composer install --no-dev

# Establece los permisos adecuados en los directorios de almacenamiento de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Habilita el módulo de reescritura de Apache
RUN a2enmod rewrite

# Expone el puerto 80 del contenedor
EXPOSE 80

# Define el comando para ejecutar el contenedor
CMD ["apache2-foreground"]
