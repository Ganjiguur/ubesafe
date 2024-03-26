# Use an official PHP 7.1 runtime as a parent image
FROM php:7.1-apache

# Set the working directory in the container to /var/www/html
WORKDIR /var/www/html

# Enable Apache mod_rewrite and mod_headers
RUN a2enmod rewrite headers

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install necessary packages
# Add libicu-dev for intl extension
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    libicu-dev \
    && rm -rf /var/lib/apt/lists/* 

# Add intl to the list of extensions to install
RUN docker-php-ext-install -j$(nproc) iconv pdo_mysql zip intl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Copy the current directory contents into the container at /var/www/html
COPY . /var/www/html

# Set permissions for the logs directory
RUN chown -R www-data:www-data /var/www/html/logs
RUN chmod -R 755 /var/www/html/logs

# Make port 80 available to the world outside this container
EXPOSE 80