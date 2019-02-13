#!/bin/bash

#cd /var/www/html
#rm -f .env
#cp .env_custom .env
#php artisan migrate --force
cron & tail -f /var/log/cron.log & /entrypoint.sh
