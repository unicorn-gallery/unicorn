FROM php:5.6-fpm

RUN apt-get update && \
    apt-get install git zip libpng-dev -y

RUN docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php \
        && mv composer.phar /usr/local/bin/ \
        && ln -s /usr/local/bin/composer.phar /usr/local/bin/composer


COPY . /app
WORKDIR /app
RUN composer install --no-interaction -o
