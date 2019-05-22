FROM atisapply/nginx-php-supervisor:1
MAINTAINER Arnis Lielturks <arnis.lielturks@gmail.com>
#FROM nginx:latest

RUN apt-get update && apt-get install -y php-gd
RUN apt-get -y install php7.2-zip

#COPY app /var/www/html/app
#COPY artisan /var/www/html/app/artisan
#COPY bootstrap /var/www/html/bootstrap
#COPY config /var/www/html/config
#COPY database /var/www/html/database
#COPY public /var/www/html/public
#COPY resources /var/www/html/resources
#COPY routes /var/www/html/routes
#COPY storage /var/www/html/storage
#COPY vendor /var/www/html/vendor
#COPY artisan /var/www/html/artisan
#COPY composer.json /var/www/html/composer.json

COPY entrypointcust.sh /entrypointcust.sh

RUN chmod +x /entrypointcust.sh

EXPOSE 80

WORKDIR /var/www/html/old

# Add crontab file in the cron directory
ADD cron /etc/cron.d/appcron

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/appcron

RUN /usr/bin/crontab /etc/cron.d/appcron

# Create the log file to be able to run tail
#RUN touch /var/log/cron.log
#RUN touch /var/www/html/storage/logs/laravel.log

#RUN chown -R www-data:www-data /var/www/html
#RUN chmod -R 777 /var/www/html/storage
ENTRYPOINT ["/bin/bash", "-c", "/entrypointcust.sh"]


