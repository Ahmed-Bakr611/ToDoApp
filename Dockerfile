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

# Create startup script - IMPROVED VERSION
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "================================="\n\
echo "Starting Laravel Application"\n\
echo "================================="\n\
\n\
# Wait for database\n\
echo "Step 1: Waiting for database connection..."\n\
max_attempts=30\n\
attempt=0\n\
\n\
while [ $attempt -lt $max_attempts ]; do\n\
  if pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" > /dev/null 2>&1; then\n\
    echo "✓ Database is ready!"\n\
    break\n\
  fi\n\
  echo "Database not ready yet (attempt $((attempt + 1))/$max_attempts)..."\n\
  sleep 2\n\
  attempt=$((attempt + 1))\n\
done\n\
\n\
if [ $attempt -eq $max_attempts ]; then\n\
  echo "✗ Failed to connect to database after $max_attempts attempts"\n\
  echo "WARNING: Continuing anyway..."\n\
fi\n\
\n\
# Clear any existing cache\n\
echo ""\n\
echo "Step 2: Clearing caches..."\n\
php artisan config:clear || true\n\
php artisan route:clear || true\n\
php artisan view:clear || true\n\
php artisan cache:clear || true\n\
echo "✓ Caches cleared"\n\
\n\
# Create migration files for Laravel built-in tables\n\
echo ""\n\
echo "Step 3: Creating system table migrations..."\n\
php artisan session:table --force 2>&1 | grep -v "already exists" || echo "✓ Session table migration ready"\n\
php artisan cache:table --force 2>&1 | grep -v "already exists" || echo "✓ Cache table migration ready"\n\
php artisan queue:table --force 2>&1 | grep -v "already exists" || echo "✓ Queue table migration ready"\n\
php artisan queue:failed-table --force 2>&1 | grep -v "already exists" || echo "✓ Failed jobs table migration ready"\n\
\n\
# Run migrations\n\
echo ""\n\
echo "Step 4: Running database migrations..."\n\
php artisan migrate --force\n\
echo "✓ Migrations completed"\n\
\n\
# Cache configuration\n\
echo ""\n\
echo "Step 5: Caching configurations..."\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
echo "✓ Configurations cached"\n\
\n\
# Start server\n\
echo ""\n\
echo "================================="\n\
echo "Starting server on port ${PORT:-8080}"\n\
echo "================================="\n\
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Expose port
EXPOSE 8080

CMD ["/usr/local/bin/start.sh"]
```

### Key Improvements:

1. **Uses `pg_isready`** to properly check PostgreSQL connection
2. **Forces creation** of migration files with `--force` flag
3. **Better error handling** - won't fail if files already exist
4. **Clear logging** so you can see what's happening in Render logs
5. **Clears caches first** before creating migrations

---

## Alternative: Simpler Solution (Change Session Driver)

If you don't want to deal with database sessions, change your Render environment variables:

**In Render Dashboard → Environment:**

Change:
```
SESSION_DRIVER=cookie
CACHE_STORE=array
