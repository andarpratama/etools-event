#!/usr/bin/env bash
set -e

cd /var/www/html || exit 1

export DB_CONNECTION=${DB_CONNECTION:-mysql}
export DB_HOST=${DB_HOST:-127.0.0.1}
export DB_PORT=${DB_PORT:-3306}
export DB_DATABASE=${DB_DATABASE:-etools}
export DB_USERNAME=${DB_USERNAME:-etools_user}
export DB_PASSWORD=${DB_PASSWORD:-etools_password}

echo "=========================================="
echo "Waiting for MariaDB to be ready..."
echo "DB_HOST: ${DB_HOST}"
echo "DB_PORT: ${DB_PORT}"
echo "DB_DATABASE: ${DB_DATABASE}"
echo "DB_USERNAME: ${DB_USERNAME}"
echo "=========================================="

# Wait for MariaDB to be ready
for i in {1..60}; do
    if mysqladmin ping -h localhost --silent 2>/dev/null; then
        echo "✓ MariaDB is ready!"
        break
    fi
    echo "Waiting for MariaDB... ($i/60)"
    sleep 1
done

# Ensure root has password set (try with unix_socket first, then set password)
echo "Setting up root password..."
mysql -u root <<EOF 2>/dev/null || true
ALTER USER 'root'@'localhost' IDENTIFIED BY 'root';
FLUSH PRIVILEGES;
EOF

# Create database if not exists
echo "Creating database and user..."
# Try with password first, then without password (for unix_socket auth)
mysql -u root -proot -e "CREATE DATABASE IF NOT EXISTS etools CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null || \
mysql -u root -e "CREATE DATABASE IF NOT EXISTS etools CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null || true

mysql -u root -proot -e "CREATE USER IF NOT EXISTS 'etools_user'@'localhost' IDENTIFIED BY 'etools_password';" 2>/dev/null || \
mysql -u root -e "CREATE USER IF NOT EXISTS 'etools_user'@'localhost' IDENTIFIED BY 'etools_password';" 2>/dev/null || true

mysql -u root -proot -e "GRANT ALL PRIVILEGES ON etools.* TO 'etools_user'@'localhost';" 2>/dev/null || \
mysql -u root -e "GRANT ALL PRIVILEGES ON etools.* TO 'etools_user'@'localhost';" 2>/dev/null || true

mysql -u root -proot -e "FLUSH PRIVILEGES;" 2>/dev/null || \
mysql -u root -e "FLUSH PRIVILEGES;" 2>/dev/null || true

echo "✓ MariaDB database and user configured"

# Run migrations and seeding
echo "=========================================="
echo "Running database migrations..."
echo "=========================================="
php artisan migrate --force || echo "✗ Migration failed"

echo "=========================================="
echo "Seeding database..."
echo "=========================================="
php artisan db:seed --class=ToolSeeder --force || echo "✗ Seeding failed"

echo "✓ Database setup completed"
