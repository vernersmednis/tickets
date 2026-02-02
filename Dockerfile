# ===========================================
# ENTRY-LEVEL DOCKER SETUP FOR LARAVEL
# ===========================================
# This Dockerfile uses PHP with Apache built-in.
# It's simple and easy to understand for beginners.

# Start from official PHP image with Apache
FROM php:8.4-apache

# Install system dependencies and PHP extensions Laravel needs
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite (needed for Laravel routes)
RUN a2enmod rewrite

# Install Composer (PHP package manager)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (for better caching)
COPY composer.json ./

# Install PHP dependencies (update instead of install to regenerate lock file for PHP 8.4)
RUN composer update --no-dev --optimize-autoloader --no-scripts

# Copy all project files into the container
COPY . .

# Run composer scripts now that all files are present
RUN composer dump-autoload --optimize

# Install Node dependencies and build frontend assets
RUN npm install && npm run build

# Set correct permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Configure Apache to serve Laravel from /public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
