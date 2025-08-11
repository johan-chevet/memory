FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql
# Copy project files into the container
COPY . /var/www/html/


# Set working directory
WORKDIR /var/www/html/

#VOLUME . /var/www/html
# Expose port 80
EXPOSE 80