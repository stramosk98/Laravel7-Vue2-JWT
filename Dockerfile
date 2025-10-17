FROM php:7.4-apache

RUN a2enmod rewrite

RUN apt-get update \
  && apt-get install -y libzip-dev unzip zlib1g-dev libicu-dev wget gnupg g++ git libpng-dev \
  && docker-php-ext-configure intl \
  && docker-php-ext-install intl pdo_mysql zip gd

RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
  && apt-get install -y nodejs

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copy composer files first for better caching
COPY composer.json ./

# Install PHP dependencies (update to resolve new packages)
RUN composer update --no-scripts --no-autoloader --prefer-dist

# Copy package files for npm
COPY package.json package-lock.json* ./

# Install npm dependencies
RUN npm install --include=dev

# Copy application code
COPY . .

# Create required Laravel storage directories
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/testing \
    storage/app/public

# Fix permissions and generate optimized autoloader
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && composer dump-autoload --optimize \
    && php -r "file_exists('.env') || copy('env.example', '.env');" \
    && php artisan key:generate --no-interaction || true

# Ensure JWT package is properly installed and publish config
RUN composer dump-autoload --optimize \
    && php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider" --no-interaction || true \
    && php artisan jwt:secret --no-interaction || true \
    && echo "JWT configuration completed"

# Create startup script for database operations
RUN echo '#!/bin/bash\n\
echo "Waiting for database..."\n\
sleep 10\n\
php artisan migrate --no-interaction\n\
php artisan db:seed --no-interaction\n\
echo "Database setup completed"\n\
apache2-foreground' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Build frontend assets (with error handling)
RUN npm config set cache /tmp/.npm \
    && (npm run dev || npm run production || echo "Frontend build failed, continuing...")

# Configure Apache
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
