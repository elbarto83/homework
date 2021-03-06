FROM php:7.4.14-cli-alpine3.12

RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \ 
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && pecl install pcov && docker-php-ext-enable pcov \
  && apk del pcre-dev ${PHPIZE_DEPS}


RUN mkdir /app
WORKDIR /app
COPY app .
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN composer install && composer update

