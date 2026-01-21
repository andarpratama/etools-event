#!/usr/bin/env bash
set -e

PORT="${PORT:-8080}"

sed -i -E "s/^Listen[[:space:]]+[0-9]+/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i -E "s/<VirtualHost \\*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

cd /var/www/html || exit 1

# Start MySQL service
echo "=========================================="
echo "Starting MySQL service..."
echo "=========================================="
service mysql start || true

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
for i in {1..30}; do
    if mysqladmin ping -h localhost --silent; then
        echo "✓ MySQL is ready!"
        break
    fi
    echo "Waiting for MySQL... ($i/30)"
    sleep 1
done

# Create database if not exists
mysql -u root -e "CREATE DATABASE IF NOT EXISTS etools CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" || true
mysql -u root -e "CREATE USER IF NOT EXISTS 'etools_user'@'localhost' IDENTIFIED BY 'etools_password';" || true
mysql -u root -e "GRANT ALL PRIVILEGES ON etools.* TO 'etools_user'@'localhost';" || true
mysql -u root -e "FLUSH PRIVILEGES;" || true

echo "MySQL database and user configured"

# Set default database config if not set (for Docker MySQL)
export DB_CONNECTION=${DB_CONNECTION:-mysql}
export DB_HOST=${DB_HOST:-127.0.0.1}
export DB_PORT=${DB_PORT:-3306}
export DB_DATABASE=${DB_DATABASE:-etools}
export DB_USERNAME=${DB_USERNAME:-etools_user}
export DB_PASSWORD=${DB_PASSWORD:-etools_password}

php artisan config:clear || true
php artisan cache:clear || true

wait_for_db() {
    echo "=========================================="
    echo "Checking database connection..."
    echo "DB_CONNECTION: ${DB_CONNECTION:-not set}"
    echo "DB_HOST: ${DB_HOST:-not set}"
    echo "DB_PORT: ${DB_PORT:-not set}"
    echo "DB_DATABASE: ${DB_DATABASE:-not set}"
    echo "DB_USERNAME: ${DB_USERNAME:-not set}"
    echo "DB_PASSWORD: ${DB_PASSWORD:+***hidden***}"
    echo "=========================================="
    echo "NOTE: If DB_HOST is 127.0.0.1, please set it to the internal database hostname from Render dashboard"
    echo "=========================================="
    
    max_attempts=30
    attempt=0
    
    while [ $attempt -lt $max_attempts ]; do
        echo "[Attempt $((attempt + 1))/$max_attempts] Testing database connection..."
        
        # Try to connect and show detailed output
        if php artisan db:show 2>&1; then
            echo "✓ Database connection successful!"
            echo "Database is ready - proceeding with migrations"
            return 0
        else
            DB_ERROR=$(php artisan db:show 2>&1 || true)
            echo "✗ Database connection failed: ${DB_ERROR}"
        fi
        
        attempt=$((attempt + 1))
        if [ $attempt -lt $max_attempts ]; then
            echo "Waiting 2 seconds before retry..."
            sleep 2
        fi
    done
    
    echo "=========================================="
    echo "✗ ERROR: Could not connect to database after $max_attempts attempts"
    echo "Please check your database configuration:"
    echo "  - DB_HOST: ${DB_HOST:-not set}"
    echo "  - DB_PORT: ${DB_PORT:-not set}"
    echo "  - DB_DATABASE: ${DB_DATABASE:-not set}"
    echo "  - DB_USERNAME: ${DB_USERNAME:-not set}"
    echo "=========================================="
    return 1
}

if wait_for_db; then
    echo "=========================================="
    echo "Running database migrations..."
    echo "=========================================="
    if php artisan migrate --force; then
        echo "✓ Migrations completed successfully"
    else
        echo "✗ Migration failed, but continuing..."
    fi
    
    echo "=========================================="
    echo "Seeding database..."
    echo "=========================================="
    if php artisan db:seed --class=ToolSeeder --force; then
        echo "✓ Database seeding completed successfully"
    else
        echo "✗ Seeding failed or skipped"
    fi
else
    echo "=========================================="
    echo "⚠ WARNING: Skipping migrations and seeding"
    echo "Application will start but database features may not work"
    echo "=========================================="
fi

php artisan config:cache || true
php artisan route:cache || true

# Skip view:cache if composer.json doesn't exist (Docker build scenario)
if [ -f composer.json ]; then
    php artisan view:cache || true
else
    echo "Skipping view:cache (composer.json not found in Docker image)"
fi

if [ ! -L public/storage ]; then
    php artisan storage:link || true
fi

echo "Starting Apache on port ${PORT}..."
echo "Apache configuration:"
grep -E "^Listen|^<VirtualHost" /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf || true

apache2ctl -t || true

# Use supervisor to manage both MySQL and Apache
echo "=========================================="
echo "Starting services with supervisor..."
echo "=========================================="
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

