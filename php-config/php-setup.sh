#! /bin/bash

# Update all the packages
apt-get update -y && apt-get upgrade -y

# Install some required dependencies
apt-get install -y \
    zip \
    unzip \
    wget \
    git \
    zlib1g-dev \
    libxml2-dev \
    libgd-dev \
    libpng-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libzip-dev

# Install xdebug
pecl install xdebug

# Install & enable PHP MySQL extensions
docker-php-ext-install mysqli pdo_mysql iconv simplexml
docker-php-ext-configure zip --with-libzip
docker-php-ext-install gd zip
docker-php-ext-enable xdebug
apt-get clean all
rm -rvf /var/lib/apt/lists/*
a2enmod rewrite headers
