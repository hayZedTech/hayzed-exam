# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Copy project files into the container
COPY . /var/www/html/

# Enable PDO for PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql

# Set proper permissions (optional, but can prevent some errors)
RUN chown -R www-data:www-data /var/www/html/

# Expose Apache port
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
