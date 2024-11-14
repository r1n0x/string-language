FROM php:8.3-fpm
RUN apt-get update
RUN apt-get install -y nano wget iputils-ping git unzip zip
COPY --from=composer:2.8.1 /usr/bin/composer /usr/local/bin/composer
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN echo "xdebug.mode = coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini