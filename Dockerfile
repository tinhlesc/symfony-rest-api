FROM php:7.3-fpm

# install some base extensions
RUN apt-get update && \
	apt-get install -y --no-install-recommends \
	libxslt1-dev libedit-dev libxml2-dev \
	libmcrypt-dev zlib1g-dev unzip git \
	libssl-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev

RUN docker-php-ext-install -j$(nproc) iconv && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install soap
RUN docker-php-ext-install xml
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql

RUN docker-php-ext-install bcmath
RUN docker-php-ext-install ctype
RUN docker-php-ext-install fileinfo
RUN docker-php-ext-install json
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install tokenizer
RUN docker-php-ext-install simplexml

# install xdebug
RUN docker-php-ext-install opcache
RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

# install redis
RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis

# install composer
RUN echo "Install Composer"
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

EXPOSE 9000
CMD ["php-fpm"]
