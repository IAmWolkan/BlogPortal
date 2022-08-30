FROM php:8.0-fpm

RUN apt update && apt install -y git zip gcc make autoconf libc-dev pkg-config libyaml-dev

RUN docker-php-ext-install pdo_mysql && \
    docker-php-ext-install mysqli

RUN pecl channel-update pecl.php.net
RUN pecl install yaml && docker-php-ext-enable yaml

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
