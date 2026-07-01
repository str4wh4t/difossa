FROM php:8.5.5-fpm

RUN apt-get update && apt-get install -y \
    supervisor \
    mariadb-client \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libicu-dev \
    libexif-dev \
    libmagickwand-dev \
    locales \
    && pecl install imagick redis \
    && docker-php-ext-install intl pdo pdo_mysql zip exif bcmath \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-enable imagick redis gd

RUN echo "id_ID.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen id_ID.UTF-8 && \
    update-locale LANG=id_ID.UTF-8

ENV LANG=id_ID.UTF-8
ENV LANGUAGE=id_ID:en
ENV LC_ALL=id_ID.UTF-8

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN ARCH=$(uname -m) && \
    DISTRO="linux-$([ "$ARCH" = "aarch64" ] && echo arm64 || echo x64)" && \
    NODE_VERSION=22.14.0 && \
    curl -fsSL https://nodejs.org/dist/v$NODE_VERSION/node-v$NODE_VERSION-$DISTRO.tar.xz -o node.tar.xz && \
    mkdir -p /usr/local/lib/nodejs && \
    tar -xJf node.tar.xz -C /usr/local/lib/nodejs && \
    rm node.tar.xz && \
    ln -s /usr/local/lib/nodejs/node-v$NODE_VERSION-$DISTRO/bin/node /usr/bin/node && \
    ln -s /usr/local/lib/nodejs/node-v$NODE_VERSION-$DISTRO/bin/npm /usr/bin/npm && \
    ln -s /usr/local/lib/nodejs/node-v$NODE_VERSION-$DISTRO/bin/npx /usr/bin/npx

WORKDIR /var/www/html

COPY . .

COPY ./builder/local/php.ini /usr/local/etc/php/conf.d/custom-php.ini

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

COPY ./builder/local/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY ./builder/local/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/entrypoint.sh"]
