# ./docker/php/Dockerfile
FROM composer:latest as composer

FROM php:7.3-fpm

RUN docker-php-ext-install pdo_mysql

RUN pecl install apcu

RUN apt-get update && \
apt-get install -y zlib1g-dev libzip-dev

RUN docker-php-ext-install zip
RUN docker-php-ext-enable apcu

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /usr/src/app
COPY . /usr/src/app

RUN PATH=$PATH:/usr/src/apps/vendor/bin:bin
