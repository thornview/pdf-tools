FROM php:5.6-apache
COPY pdf-tools/ /var/www/html/
COPY php.ini /usr/local/etc/php/

RUN apt-get update && apt-get -y install build-essential gcj-jdk unzip wget
RUN wget http://www.pdflabs.com/tools/pdftk-the-pdf-toolkit/pdftk-2.02-src.zip && unzip pdftk-2.02-src.zip
RUN sed -i 's/VERSUFF=-4.6/VERSUFF=-4.9/g' pdftk-2.02-dist/pdftk/Makefile.Debian
RUN cd pdftk-2.02-dist/pdftk && make -f Makefile.Debian && make -f Makefile.Debian install
RUN rm -rf pdftk-2.02-dist pdftk-2.02-src.zip && apt-get clean
