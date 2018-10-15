FROM php:5.6-fpm

RUN apt-get update && \
    apt-get install -y git \
    zip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd
RUN echo "date.timezone = UTC" > /usr/local/etc/php/conf.d/date.timezone.ini

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/ \
    && ln -s /usr/local/bin/composer.phar /usr/local/bin/composer

COPY composer.json composer.lock /app/
WORKDIR /app
RUN chown -R www-data:www-data /app

ENV COMPOSER_ALLOW_SUPERUSER 1
CMD bash -c "composer install && php-fpm"\
