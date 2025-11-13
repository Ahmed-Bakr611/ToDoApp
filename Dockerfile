# Stage 1 - Build Frontend (Vite)
FROM node:18 AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2 - Backend (Laravel + PHP)
FROM php:8.2-cli AS backend

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    postgresql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring zip gd exif pcntl bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy composer files first (for better caching)
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy app files
COPY . .

# Copy built frontend from Stage 1
COPY --from=frontend /app/public/build ./public/build

# Run composer scripts
RUN composer dump-autoload --optimize

# Set proper permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Create startup script with database wait and session table creation
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "Waiting for database..."\n\
max_attempts=30\n\
attempt=0\n\
until php artisan migrate:status 2>/dev/null || [ $attempt -eq $max_attempts ]; do\n\
  echo "Database is unavailable - sleeping (attempt $attempt/$max_attempts)"\n\
  sleep 2\n\
  attempt=$((attempt + 1))\n\
done\n\
\n\
if [ $attempt -eq $max_attempts ]; then\n\
  echo "Failed to connect to database after $max_attempts attempts"\n\
  echo "Starting server anyway..."\n\
else\n\
  echo "Database is up!"\n\
  \n\
  echo "Publishing session migration..."\n\
  php artisan session:table 2>/dev/null || true\n\
  \n\
  echo "Publishing cache migration..."\n\
  php artisan cache:table 2>/dev/null || true\n\
  \n\
  echo "Publishing queue migration..."\n\
  php artisan queue:table 2>/dev/null || true\n\
  \n\
  echo "Running migrations..."\n\
  php artisan migrate --force\n\
fi\n\
\n\
echo "Caching configuration..."\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
echo "Starting server on port ${PORT:-8080}..."\n\
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Expose port
EXPOSE 8080

CMD ["/usr/local/bin/start.sh"]
