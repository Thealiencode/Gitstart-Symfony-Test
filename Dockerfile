# Use an official PHP image as the base image
FROM php:8.1-fpm

# Set the working directory in the container
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update \
    && apt-get install -y \
    libicu-dev \
    zlib1g-dev \
    libzip-dev \
    nginx\
    unzip\
    librabbitmq-dev\
    supervisor

# Enable necessary PHP extensions
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl pdo pdo_mysql zip sockets

RUN pecl install amqp

# Add the amqp extension configuration to php.ini
RUN echo "extension=amqp.so" > /usr/local/etc/php/php.ini

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the Symfony app files to the container
COPY . /var/www/html/

# Set up Nginx configuration
COPY ./nginx.conf /etc/nginx/sites-available/default

# Copy Supervisor configuration
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Your existing Dockerfile instructions...

# Start Supervisor
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Copy the entry point file
COPY docker-entrypoint.sh /usr/local/bin/

# Give execution permissions to the entry point file
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# run composer install
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install 

# Expose port 8000 for the Symfony development server
EXPOSE 8000

# Set the entry point
ENTRYPOINT ["docker-entrypoint.sh"]
