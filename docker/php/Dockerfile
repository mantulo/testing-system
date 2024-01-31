FROM php:8.3-fpm-alpine

WORKDIR /app

RUN apk add libpq-dev
RUN apk add icu-dev
RUN docker-php-ext-install bcmath intl pdo pdo_pgsql

RUN curl -sSL https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions -o - | sh -s \
      decimal

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

CMD ["php-fpm"]