# Use PHP 8.3 CLI as base
FROM php:8.3-cli

# Set working directory
WORKDIR /var/www

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application code
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Create symbolic link for storage
RUN php artisan storage:link || true

# Run migrations (optional; using --force in production)
RUN php artisan migrate --force || true

# Expose port
EXPOSE 10000

# Default command
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
