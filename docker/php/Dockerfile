FROM php:8.4-fpm

ARG UID
ARG GID

# System deps
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libpq-dev librabbitmq-dev postgresql-client netcat-openbsd libicu-dev \
    && docker-php-ext-install pdo pdo_pgsql zip intl \
    && pecl install amqp && docker-php-ext-enable amqp \
    && rm -rf /var/lib/apt/lists/*

# Create www-data user with matching UID:GID
RUN groupmod -g ${GID} www-data && usermod -u ${UID} -g www-data www-data

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set up Composer cache directory
RUN mkdir -p /var/www/.composer && \
    chown -R www-data:www-data /var/www/.composer

# Copy entrypoint
COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

WORKDIR /var/www/pizzouille-api

EXPOSE 9000

CMD ["php-fpm"]

USER www-data
