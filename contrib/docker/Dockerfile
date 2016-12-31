FROM php:7-fpm-alpine

RUN apk add --no-cache \
  bash

RUN wget http://getcomposer.org/installer \
  && php installer -- --install-dir=/usr/local/bin --filename=composer \
  && rm installer

RUN apk add --no-cache python python-dev py-setuptools \
  && if [[ ! -e /usr/bin/python ]]; then ln -sf /usr/bin/python2.7 /usr/bin/python; fi \
  && if [[ ! -e /usr/bin/python-config ]]; then ln -sf /usr/bin/python2.7-config /usr/bin/python-config; fi \
  && if [[ ! -e /usr/bin/easy_install ]]; then ln -sf /usr/bin/easy_install-2.7 /usr/bin/easy_install; fi \
  && easy_install pip \
  && pip install --upgrade pip \
  && pip install luigi \
  && if [[ ! -e /usr/bin/pip ]]; then ln -sf /usr/bin/pip2.7 /usr/bin/pip; fi

COPY docker-entrypoint.sh /usr/local/bin/

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]
