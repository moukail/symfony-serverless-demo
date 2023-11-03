FROM node:lts-alpine3.18 AS node
FROM php:8.2-fpm-alpine3.18

ENV TIMEZONE 'Europe/Amsterdam'

RUN apk --update --no-cache add bash wget gcc g++ make autoconf linux-headers openssh git rsync
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN docker-php-ext-enable opcache

# ext-intl
#RUN apk --update --no-cache add icu-dev && docker-php-ext-install -j$(nproc) intl

# For redis
#RUN pecl install redis && docker-php-ext-enable redis

# If you want to use sodium for password hashing instead of becrypt.
#RUN apk --update --no-cache add libsodium-dev
#RUN pecl install libsodium

# For Symfony/messenger
#RUN apk --update --no-cache add rabbitmq-c-dev rabbitmq-c
#RUN pecl install amqp && docker-php-ext-enable amqp

# For Xdebug >=3.2 support PHP 8.0
RUN pecl install xdebug && docker-php-ext-enable xdebug

# For Xdebug 3
RUN echo $'zend_extension=xdebug.so \n\
xdebug.mode=debug,coverage \n\
xdebug.idekey=PHPSTORM \n\
xdebug.start_with_request=trigger \n\
xdebug.client_host=host.docker.internal'\
> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && sed -i "s|;date.timezone =.*|date.timezone = ${TIMEZONE}|" /usr/local/etc/php/php.ini \
    && sed -i "s|memory_limit =.*|memory_limit = -1|" /usr/local/etc/php/php.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony5/bin/symfony /usr/bin/symfony

### Codeception
RUN wget http://codeception.com/codecept.phar -O /usr/bin/codecept && chmod a+x /usr/bin/codecept
#source <(codecept _completion --generate-hook --program codecept)

# Basic smoke test
COPY --from=node /usr/local /usr/local
COPY --from=node /opt /opt

RUN addgroup _www && adduser -S -H --ingroup _www _www

RUN echo | npm install -g npm

WORKDIR /var/www

RUN git config --global --add safe.directory /var/www

ADD install.sh /home/
RUN chmod +x /home/install.sh

ADD docker-init.sh /home/
RUN chmod +x /home/docker-init.sh
CMD bash /home/docker-init.sh
