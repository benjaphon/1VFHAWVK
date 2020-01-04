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
        vim

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd