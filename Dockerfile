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

# Install PHP dependencies
RUN composer update --no-scripts --no-autoloader --prefer-dist

# Copy package files for npm
COPY package.json ./

# Install npm dependencies
RUN npm install --include=dev

# Copy application code
COPY . .

# Fix permissions and generate optimized autoloader
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && composer dump-autoload --optimize \
    && php -r "file_exists('.env') || copy('env.example', '.env');" \
    && php artisan key:generate --no-interaction || true

# Build frontend assets
RUN npm config set cache /tmp/.npm \
    && npm run dev

# Configure Apache
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
