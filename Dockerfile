# ===========================================================
# Stage 1 - Build Frontend (Vite)
# ===========================================================
FROM node:18-alpine AS frontend

WORKDIR /app
COPY package*.json ./
RUN npm ci --only=production

COPY . .
RUN npm run build

# ===========================================================
# Stage 2 - Backend (Laravel + PHP-FPM + Nginx)
# ===========================================================
FROM php:8.2-fpm-alpine AS backend

# -----------------------------------------------------------
# Install system dependencies
# -----------------------------------------------------------
RUN apk add --no-cache \
    nginx \
    supervisor \
    postgresql-client \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    bash \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring zip gd exif pcntl bcmath opcache

# -----------------------------------------------------------
# Install Composer
# -----------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -----------------------------------------------------------
# Configure PHP for production
# -----------------------------------------------------------
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www

# -----------------------------------------------------------
# Install PHP dependencies
# -----------------------------------------------------------
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# -----------------------------------------------------------
# Copy Laravel application
# -----------------------------------------------------------
COPY . .
COPY --from=frontend /app/public/build ./public/build

RUN composer dump-autoload --optimize

# -----------------------------------------------------------
# Permissions
# -----------------------------------------------------------
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# -----------------------------------------------------------
# Nginx Configuration
# -----------------------------------------------------------
RUN echo 'server {\n\
    listen 8080;\n\
    server_name _;\n\
    root /var/www/public;\n\
    index index.php;\n\
\n\
    client_max_body_size 10M;\n\
\n\
    location / {\n\
        try_files $uri $uri/ /index.php?$query_string;\n\
    }\n\
\n\
    location ~ \.php$ {\n\
        include fastcgi_params;\n\
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_index index.php;\n\
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;\n\
        fastcgi_param PATH_INFO $fastcgi_path_info;\n\
    }\n\
\n\
    location ~ /\.(?!well-known).* {\n\
        deny all;\n\
    }\n\
}' > /etc/nginx/http.d/default.conf

# -----------------------------------------------------------
# Supervisor Configuration
# -----------------------------------------------------------
RUN echo '[supervisord]\n\
nodaemon=true\n\
user=root\n\
logfile=/dev/stdout\n\
logfile_maxbytes=0\n\
\n\
[program:php-fpm]\n\
command=php-fpm -F\n\
autostart=true\n\
autorestart=true\n\
stdout_logfile=/dev/stdout\n\
stderr_logfile=/dev/stderr\n\
\n\
[program:nginx]\n\
command=nginx -g "daemon off;"\n\
autostart=true\n\
autorestart=true\n\
stdout_logfile=/dev/stdout\n\
stderr_logfile=/dev/stderr' > /etc/supervisord.conf

# -----------------------------------------------------------
# Startup Script
# -----------------------------------------------------------
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "================================="\n\
echo "Starting Laravel Application"\n\
echo "================================="\n\
\n\
# Wait for PostgreSQL to become available\n\
echo "Waiting for database..."\n\
for i in {1..30}; do\n\
  if pg_isready -h "$DB_HOST" -p "${DB_PORT:-5432}" -U "$DB_USERNAME" > /dev/null 2>&1; then\n\
    echo "✓ Database connected"\n\
    break\n\
  fi\n\
  echo "Database not ready yet... ($i)"; sleep 2;\n\
done\n\
\n\
# Run migrations\n\
echo "Running migrations..."\n\
php artisan migrate --force --no-interaction || echo "⚠️  Migration failed (possibly already migrated)"\n\
\n\
# Cache optimizations\n\
echo "Caching configurations..."\n\
php artisan config:clear || true\n\
php artisan cache:clear || true\n\
php artisan route:cache || true\n\
php artisan view:cache || true\n\
\n\
echo "✓ Cache complete"\n\
\n\
# Start Supervisor\n\
echo "Starting services..."\n\
exec /usr/bin/supervisord -c /etc/supervisord.conf' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

EXPOSE 8080
CMD ["/usr/local/bin/start.sh"]
