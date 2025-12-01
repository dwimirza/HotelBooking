FROM php:8.2-fpm

# Install PHP extensions needed for your app (e.g., mysqli, pdo, pdo_mysql)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy custom PHP configuration
COPY custom-php.ini /usr/local/etc/php/conf.d/custom-php.ini

# Copy application code to container
COPY . /var/www/html/
