FROM php:7.3.4-fpm-stretch

RUN apt update && \
    apt install -y zlib1g-dev libzip-dev libpq-dev

# php extensions
RUN docker-php-ext-install pdo_pgsql pdo_mysql bcmath zip mbstring opcache

# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# xdebug
RUN pecl install xdebug-2.7.1 \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.force_display_errors=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.extended_info=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/symfony
CMD ["php-fpm"]