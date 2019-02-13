FROM php:7.2.14-fpm

RUN apt-get update && apt-get -y install apt-transport-https ca-certificates gnupg-agent software-properties-common
RUN apt-get install -y libxml2-dev libmcrypt-dev zlib1g-dev \
    mysql-client --no-install-recommends \
    && docker-php-ext-install pdo_mysql zip dom intl
RUN groupadd --gid 999 docker
RUN usermod -aG docker www-data