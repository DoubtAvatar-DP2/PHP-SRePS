FROM php:7.4-apache

# Update all the packages
RUN apt-get update -y && apt-get upgrade -y

# Install some required dependencies
RUN apt-get install -y zip unzip wget git zlib1g-dev libxml2-dev libgd-dev libpng-dev libfreetype6-dev libjpeg62-turbo-dev libzip-dev

# Install xdebug
RUN pecl install xdebug

# Install & enable PHP MySQL extensions
RUN docker-php-ext-install mysqli pdo_mysql iconv simplexml
RUN docker-php-ext-configure zip
RUN docker-php-ext-install gd zip
RUN docker-php-ext-enable xdebug
RUN apt-get clean all
RUN rm -rvf /var/lib/apt/lists/*
RUN a2enmod rewrite headers
RUN service apache2 restart
