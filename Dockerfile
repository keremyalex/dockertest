FROM php:8.1-fpm

# Instalar dependencias requeridas para las extensiones de PHP
RUN apt-get update \
    && apt-get install -y \
        zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*

# Configurar variables de entorno para zlib
ENV ZLIB_CFLAGS="-I/usr/include"
ENV ZLIB_LIBS="-L/usr/lib/x86_64-linux-gnu"

# Instalar las extensiones de PHP requeridas
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

# Copiar el código de tu proyecto a la carpeta de trabajo del contenedor
COPY . /var/www/html

# Configurar los permisos adecuados para los archivos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exponer el puerto 9000 para el servidor PHP-FPM
EXPOSE 9000

# Ejecutar el servidor PHP-FPM
CMD ["php-fpm"]
