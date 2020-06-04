FROM php:7.2-apache
RUN a2enmod rewrite

RUN apt-get update && \
    apt-get install -y -qq git \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libmcrypt-dev \
        libpng-dev \
        zip unzip \
        nodejs \
        wget \
        vim \
        && pecl install xdebug \
        && rm -rf /tmp/pear \
        && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)\n" >> /usr/local/etc/php/conf.d/xdebug.ini

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd