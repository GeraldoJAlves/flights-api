
FROM php:7.3-fpm-alpine

RUN apk update
RUN apk add \
        libzip-dev \
        zip \
        curl \
  && docker-php-ext-install mbstring zip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./scripts /scripts/

RUN chmod -Rf 755 /scripts

WORKDIR /var/www/html/app

CMD ["/scripts/start.sh"]