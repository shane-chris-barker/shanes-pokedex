FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    g++ \
    git \
    libicu-dev \
    libzip-dev \
    unzip \
    curl \
 && docker-php-ext-install \
    intl \
    opcache \
    pdo \
    pdo_mysql \
    zip \
 && pecl install apcu \
 && docker-php-ext-enable apcu \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*


WORKDIR /var/www/shanes-pokedex

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer \
 && curl -sS https://get.symfony.com/cli/installer | bash \
 && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony


RUN chown -R www-data:www-data /var/www/shanes-pokedex
