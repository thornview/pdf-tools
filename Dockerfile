FROM php:5.6-apache

COPY config/php.ini /usr/local/etc/php/
COPY forms/ /var/www/html/forms/
COPY index.php /var/www/html/
COPY lib/ /var/www/html/lib/
COPY public/ /var/www/html/public/
COPY temp/ /var/www/html/temp/
COPY vendor/ /var/www/html/vendor/

RUN apt-get update && apt-get -y install pdftk

RUN chmod 777 /var/www/html/temp/
RUN chmod 777 /var/www/html/forms/
