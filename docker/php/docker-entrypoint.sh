#!/bin/sh
set -e

echo "🚀 Running entrypoint as $(whoami)"

# Ensure writable dirs are owned by www-data
for dir in /var/www/pizzouille-api/var /var/www/pizzouille-api/vendor; do
    if [ -d "$dir" ]; then
        echo "🔧 Fixing permissions on $dir"
        chown -R www-data:www-data "$dir"
    fi
done

# Wait for PostgreSQL
until pg_isready -h db -U "$POSTGRES_USER"; do
    echo "⏳ Waiting for PostgreSQL..."
    sleep 2
done

# Wait for RabbitMQ
until nc -z rabbitmq 5672; do
    echo "⏳ Waiting for RabbitMQ..."
    sleep 2
done

# If no command is provided, run php-fpm
if [ $# -eq 0 ]; then
  set -- php-fpm
fi

exec "$@"
