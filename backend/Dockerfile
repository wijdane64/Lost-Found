# 1️⃣ Base image PHP-FPM
FROM php:8.2-fpm

# 2️⃣ Set working directory
WORKDIR /var/www/html

# 3️⃣ Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    npm \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# 4️⃣ Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5️⃣ Copy project files
COPY . .

# 6️⃣ Install PHP dependencies
RUN composer install

# 7️⃣ Set permissions for storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8️⃣ Expose port 9000 (PHP-FPM)
EXPOSE 9000

# 9️⃣ Start PHP-FPM
CMD ["php-fpm"]
