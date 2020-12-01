
FROM php:7.3-fpm-alpine
# lumen packages
RUN apk update
RUN apk add \
        libzip-dev \
        zip \
        curl \
  && docker-php-ext-install mbstring zip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer