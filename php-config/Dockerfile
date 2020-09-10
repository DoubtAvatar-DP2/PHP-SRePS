FROM php:7.4-apache

COPY php-setup.sh /root/
RUN chmod +x ~/php-setup.sh
RUN bash /root/php-setup.sh
RUN rm /root/php-setup.sh
