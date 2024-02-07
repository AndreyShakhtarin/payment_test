# Use an official PHP image
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html/public
ENV APP_HOME /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install Symfony
RUN apt-get update && \
    apt-get install -y \
        git \
        unzip \
    && curl -sS https://get.symfony.com/cli/installer | bash

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# put apache and php config for Symfony, enable sites
COPY ./docker/general/symfony.conf /etc/apache2/sites-available/symfony.conf
RUN a2dissite 000-default.conf
RUN a2ensite symfony.conf

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony dependencies
RUN composer install

# enable apache modules
RUN a2enmod rewrite