FROM richarvey/nginx-php-fpm:1.9.1

LABEL maintainer="Alex Neo <me@alexneo.net>"

COPY . /var/www

RUN apk update
RUN apk add stress-ng
