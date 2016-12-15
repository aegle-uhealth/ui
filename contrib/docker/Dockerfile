FROM php:7-fpm-alpine

RUN apk add --no-cache \
  bash

RUN wget http://getcomposer.org/installer \
  && php installer -- --install-dir=/usr/local/bin --filename=composer \
  && rm installer

WORKDIR /var/www/html

COPY docker-entrypoint.sh /usr/local/bin/

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]
