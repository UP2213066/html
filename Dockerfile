FROM debian:latest

RUN apt-get update && \
    apt-get install -y \
        apache2 \
        php \
        libapache2-mod-php \
        php-mysql 

COPY html /var/www/html
