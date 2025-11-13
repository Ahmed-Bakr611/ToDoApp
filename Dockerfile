# Stage 1 - Build Frontend (Vite)
FROM node:18-alpine AS frontend
WORKDIR /app
COPY package*.json ./
# Install ALL dependencies (including devDependencies needed for build)
RUN npm ci
COPY . .
RUN npm run build

# Stage 2 - Backend (Laravel + PHP-FPM + Nginx)
FROM php:8.2-fpm-alpine AS backend

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    postgresql-client \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring zip gd exif pcntl bcmath opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configure PHP for production
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies (production only)
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy application code
COPY . .
COPY --from=frontend /app/public/build ./public/build

# Run Laravel post-install scripts
RUN composer dump-autoload --optimize

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Configure Nginx
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
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_index index.php;\n\
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;\n\
        include fastcgi_params;\n\
    }\n\
\n\
    location ~ /\.(?!well-known).* {\n\
        deny all;\n\
    }\n\
}' > /etc/nginx/http.d/default.conf

# Configure Supervisor
RUN echo '[supervisord]\n\
nodaemon=true\n\
user=root\n\
logfile=/dev/stdout\n\
logfile_maxbytes=0\n\
\n\
[program:php-fpm]\n\
command=php-fpm\n\
autostart=true\n\
autorestart=true\n\
stdout_logfile=/dev/stdout\n\
stdout_logfile_maxbytes=0\n\
stderr_logfile=/dev/stderr\n\
stderr_logfile_maxbytes=0\n\
\n\
[program:nginx]\n\
command=nginx -g "daemon off;"\n\
autostart=true\n\
autorestart=true\n\
stdout_logfile=/dev/stdout\n\
stdout_logfile_maxbytes=0\n\
stderr_logfile=/dev/stderr\n\
stderr_logfile_maxbytes=0' > /etc/supervisord.conf

# Create startup script with detailed debugging
RUN echo '#!/bin/sh\n\
set -e\n\
\n\
echo "================================="\n\
echo "Starting Laravel Application"\n\
echo "================================="\n\
echo "Environment: $APP_ENV"\n\
echo "Database Host: $DB_HOST"\n\
echo "Database Port: ${DB_PORT:-5432}"\n\
echo "Database Name: $DB_DATABASE"\n\
echo ""\n\
\n\
# Wait for database with better error handling\n\
echo "Step 1: Waiting for database connection..."\n\
timeout=60\n\
attempt=0\n\
while [ $timeout -gt 0 ]; do\n\
  if pg_isready -h "$DB_HOST" -p "${DB_PORT:-5432}" -U "$DB_USERNAME" -t 1 > /dev/null 2>&1; then\n\
    echo "✓ Database is ready!"\n\
    break\n\
  fi\n\
  echo "  Attempt $((61 - timeout))/60 - Database not ready yet..."\n\
  sleep 2\n\
  timeout=$((timeout - 2))\n\
done\n\
\n\
if [ $timeout -le 0 ]; then\n\
  echo "✗ FATAL: Database connection timeout after 60 seconds"\n\
  echo "  Please check your DB_HOST, DB_PORT, and database status"\n\
  exit 1\n\
fi\n\
\n\
# Test actual database connection\n\
echo ""\n\
echo "Step 2: Testing database connection..."\n\
if ! php artisan db:show 2>&1 | grep -q "Connection:"; then\n\
  echo "✗ FATAL: Laravel cannot connect to database"\n\
  echo "  Running diagnostics..."\n\
  php artisan db:show || true\n\
  exit 1\n\
fi\n\
echo "✓ Laravel connected to database successfully"\n\
\n\
# List existing migrations\n\
echo ""\n\
echo "Step 3: Checking migration files..."\n\
migration_count=$(ls -1 database/migrations/*.php 2>/dev/null | wc -l)\n\
echo "  Found $migration_count migration file(s)"\n\
if [ $migration_count -eq 0 ]; then\n\
  echo "✗ WARNING: No migration files found!"\n\
fi\n\
\n\
# Check if migrations table exists\n\
echo ""\n\
echo "Step 4: Checking migrations table..."\n\
php artisan migrate:status --no-interaction 2>&1 || echo "  Migrations table does not exist yet (will be created)"\n\
\n\
# Run migrations with verbose output\n\
echo ""\n\
echo "Step 5: Running migrations..."\n\
if php artisan migrate --force --no-interaction --verbose 2>&1; then\n\
  echo "✓ Migrations completed successfully"\n\
else\n\
  echo "✗ FATAL: Migration failed!"\n\
  echo "  Checking migration status..."\n\
  php artisan migrate:status --no-interaction || true\n\
  exit 1\n\
fi\n\
\n\
# Verify sessions table exists\n\
echo ""\n\
echo "Step 6: Verifying sessions table..."\n\
if php artisan tinker --execute="echo DB::table(\\"sessions\\")->count();" 2>&1 | grep -E "^[0-9]+$" > /dev/null; then\n\
  echo "✓ Sessions table exists and is accessible"\n\
else\n\
  echo "✗ WARNING: Sessions table verification failed"\n\
  echo "  This may cause session errors"\n\
fi\n\
\n\
# Cache configurations\n\
echo ""\n\
echo "Step 7: Caching configurations..."\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
echo "✓ Configurations cached"\n\
\n\
# Start services\n\
echo ""\n\
echo "================================="\n\
echo "✓ All checks passed!"\n\
echo "================================="\n\
echo "Starting PHP-FPM and Nginx..."\n\
echo "Application will be available on port 8080"\n\
echo ""\n\
exec /usr/bin/supervisord -c /etc/supervisord.conf' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

EXPOSE 8080

CMD ["/usr/local/bin/start.sh"]
