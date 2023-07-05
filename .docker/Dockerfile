FROM php:8.2-fpm-alpine

WORKDIR /var/www/html
RUN apk add oniguruma-dev libzip-dev autoconf g++ make

RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-enable pdo_mysql
RUN pecl install redis \
    && docker-php-ext-enable redis
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN rm -rf /var/cache/apk/*

RUN apk add shadow && usermod -u 1000 www-data && groupmod -g 1000 www-data
COPY --chown=www-data:www-data . /var/www/html
COPY  /var/www/html/.env.example /var/www/html/.env
RUN composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader

WORKDIR /var/www/html

USER www-data

EXPOSE 9000
CMD ["php-fpm"]