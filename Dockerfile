FROM php:5.6-apache
COPY forms/ /var/www/html/forms/
COPY lib/ /var/www/html/lib/
COPY public/ /var/www/html/public/
COPY temp/ /var/www/html/temp/
COPY vendor/ /var/www/html/vendor/
COPY config/php.ini /usr/local/etc/php/

RUN apt-get update && apt-get -y install pdftk

RUN chmod 777 /var/www/html/temp/
RUN chmod 777 /var/www/html/forms/
