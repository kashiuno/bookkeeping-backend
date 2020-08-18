FROM php:7.4-fpm

RUN echo "deb http://deb.debian.org/debian stretch main" >> /etc/apt/sources.list \
    echo "deb http://security.debian.org/debian-security stretch/updates main" >> /etc/apt/sources.list

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libfreetype6 \
        libfreetype6-dev

RUN apt-get install -y \
        vim \
        gcc \
        make \
        curl \
        msmtp \
        libc-dev \
        autoconf \
        libpq-dev \
        libpng-dev \
        libwebp-dev \
        libxml2-dev \
        pkg-config \
        libssl-dev \
        libzip-dev \
        libonig-dev \
        libjpeg-dev \
        libmcrypt-dev \
        libmemcached-dev \
    && pecl install mcrypt-1.0.3 \
    && docker-php-ext-configure gd \
        --with-jpeg=/usr/include \
        --with-freetype=/usr/include \
        --with-webp=/usr/include \
    && docker-php-ext-configure calendar \
    && docker-php-ext-install -j$(nproc) \
        gd \
        zip \
        soap \
        mysqli \
        opcache \
        calendar \
        pdo_mysql \
    && pecl install \
        xdebug \
        memcached \
    && docker-php-ext-enable \
        xdebug \
        memcached \
    && docker-php-source delete \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/cache/apt/*

RUN usermod -u 1000 www-data

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
