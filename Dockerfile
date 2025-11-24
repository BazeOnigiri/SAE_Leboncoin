FROM php:8.3

WORKDIR /var/www/html

# Install system deps
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev nodejs npm \
    libpng-dev libonig-dev libxml2-dev libpq-dev

# PHP extensions required by Laravel
RUN docker-php-ext-install pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# Install dependencies
RUN composer install --no-interaction
RUN npm install && npm run build

# Laravel permissions
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 9000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]
