FROM php:7.4-apache

COPY php-setup.sh /root/php-setup.sh
RUN chmod +x /root/php-setup.sh \
    && /root/php-setup.sh \
    && rm /root/php-setup.sh
