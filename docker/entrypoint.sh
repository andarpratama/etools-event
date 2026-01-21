#!/usr/bin/env bash
set -e

PORT="${PORT:-8080}"

sed -i -E "s/^Listen[[:space:]]+[0-9]+/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i -E "s/<VirtualHost \\*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

cd /var/www/html || exit 1

# Create log directories
mkdir -p /var/log/mysql /var/log/apache2
chown -R mysql:mysql /var/log/mysql 2>/dev/null || true

# Initialize MariaDB if not already initialized
if [ ! -d /var/lib/mysql/mysql ]; then
    echo "=========================================="
    echo "Initializing MariaDB..."
    echo "=========================================="
    mysql_install_db --user=mysql --datadir=/var/lib/mysql || true
    chown -R mysql:mysql /var/lib/mysql || true
fi

# Set default database config if not set (for Docker MySQL)
export DB_CONNECTION=${DB_CONNECTION:-mysql}
export DB_HOST=${DB_HOST:-127.0.0.1}
export DB_PORT=${DB_PORT:-3306}
export DB_DATABASE=${DB_DATABASE:-etools}
export DB_USERNAME=${DB_USERNAME:-etools_user}
export DB_PASSWORD=${DB_PASSWORD:-etools_password}

php artisan config:clear || true
php artisan cache:clear || true

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

