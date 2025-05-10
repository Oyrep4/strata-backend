FROM php:8.2-apache

# Enable Apache mod_rewrite (optional, good for routing if needed)
RUN a2enmod rewrite

# Copy all project files to the web root
COPY . /var/www/html/

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
