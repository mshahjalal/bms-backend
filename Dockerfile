FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    linux-headers \
    postgresql-dev \
    libzip-dev \
    unzip \
    git \
    $PHPIZE_DEPS

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql zip

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Expose port and start
EXPOSE 9000
CMD ["php-fpm"]
