FROM php:7.4-apache

COPY ./php-setup.sh /root/
RUN chmod +x ~/php-setup.sh \
    && ~/php-setup.sh \
    && rm ~/php-setup.sh
