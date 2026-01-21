#!/usr/bin/env bash
set -euo pipefail

PORT="${PORT:-8080}"

sed -i -E "s/^Listen[[:space:]]+[0-9]+/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i -E "s/<VirtualHost \\*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

cd /var/www/html

php artisan config:clear
php artisan cache:clear

wait_for_db() {
    echo "Waiting for database connection..."
    max_attempts=30
    attempt=0
    
    while [ $attempt -lt $max_attempts ]; do
        if php artisan db:show &> /dev/null; then
            echo "Database is up - executing migrations"
            return 0
        fi
        attempt=$((attempt + 1))
        echo "Database is unavailable - sleeping (attempt $attempt/$max_attempts)"
        sleep 2
    done
    
    echo "Warning: Could not connect to database after $max_attempts attempts"
    return 1
}

if wait_for_db; then
    php artisan migrate --force
    
    echo "Seeding database..."
    php artisan db:seed --class=ToolSeeder --force || echo "Seed completed or skipped"
else
    echo "Skipping migrations and seeding due to database connection issues"
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

if [ ! -L public/storage ]; then
    php artisan storage:link
fi

exec apache2-foreground

