# The FPM base container
FROM php:8.0-fpm as dev

RUN docker-php-ext-install "-j$(nproc)" pdo_mysql

WORKDIR /app

# Composer install step
FROM composer:2.0 as build

WORKDIR /app

COPY composer.* ./
COPY database/ database/

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

# npm install step
FROM node:14-alpine as node

WORKDIR /app

COPY package.json yarn.lock *.mix.js *.config.js tsconfig.json  ./
COPY resources ./resources
COPY public/developer/v1/openapi.yaml ./public/developer/v1/openapi.yaml

RUN mkdir -p /app/public \
    && yarn --production --frozen-lockfile \
    && yarn run production \
    && yarn run doc \
    && pwd && ls -l /app/public && ls -l /app/public/*

# The FPM production container
FROM dev

COPY ./docker/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY . /app
COPY --from=build /app/vendor/ /app/vendor/
COPY --from=node /app/public/js/ /app/public/js/
COPY --from=node /app/public/css/ /app/public/css/
COPY --from=node /app/mix-manifest.json /app/public/mix-manifest.json

RUN chmod -R 777 /app/storage
