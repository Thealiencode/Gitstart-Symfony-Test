#!/bin/bash
# Perform any setup or configuration tasks here

composer install 

# Start PHP-FPM
php-fpm --daemonize

service supervisor start

# Start Nginx in the foreground
nginx -g 'daemon off;'