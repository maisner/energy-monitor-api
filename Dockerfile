FROM php:7.3-apache

RUN docker-php-ext-install pdo_mysql \
&& a2enmod rewrite

COPY php.ini /usr/local/etc/php/

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update -y && apt-get install -y -qq \
	libpng-dev\
	zlib1g-dev \
	libjpeg62-turbo-dev \
	apt-transport-https \
	libfreetype6-dev \
	libmcrypt-dev \
	libssl-dev \
	libzip-dev \
	zip unzip \
	wget

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-configure zip --with-libzip
RUN docker-php-ext-install -j$(nproc) iconv gd bcmath zip

COPY . /var/www/html

WORKDIR /var/www/html

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

RUN mkdir -m 777 cache
RUN mkdir -m 777 log
