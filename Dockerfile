FROM php:8.1-fpm

# Actualizar e instalar paquetes necesarios
RUN apt-get update \
    && apt-get install -y \
        libcurl4-gnutls-dev \
        libpng-dev \
        libxml2-dev \
        libxslt-dev \
    && rm -rf /var/lib/apt/lists/*

# Configurar variables de entorno para libpng
ENV PNG_CFLAGS="-I/usr/include/libpng16"
ENV PNG_LIBS="-L/usr/lib/x86_64-linux-gnu"

# Instalar la extensión imagick
RUN pecl install imagick \
    && docker-php-ext-enable imagick

# Instalar las extensiones de PHP requeridas
RUN docker-php-ext-install \
    calendar \
    ctype \
    curl \
    dom \
    exif \
    ffi \
    fileinfo \
    filter \
    ftp \
    gd \
    gettext \
    hash \
    iconv \
    json \
    libxml \
    mysqli \
    opcache \
    pcntl \
    pdo \
    pdo_mysql \
    phar \
    posix \
    readline \
    session \
    shmop \
    simplexml \
    sockets \
    sodium \
    spl \
    standard \
    sysvmsg \
    sysvsem \
    sysvshm \
    tokenizer \
    xml \
    xmlreader \
    xmlwriter \
    xsl \
    zip \
    zlib

# Copiar el código de tu proyecto a la carpeta de trabajo del contenedor
COPY . /var/www/html

# Configurar los permisos adecuados para los archivos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exponer el puerto 9000 para el servidor PHP-FPM
EXPOSE 9000

# Ejecutar el servidor PHP-FPM
CMD ["php-fpm"]
