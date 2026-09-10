FROM dunglas/frankenphp:1-php8.4-alpine

ENV SERVER_NAME="http://:8080"
ENV APP_ENV="production"
ENV APP_DEBUG="false"
ENV LOG_CHANNEL="stderr"

WORKDIR /app

# Install dependencies (production only, no dev packages)
COPY composer.json ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Copy full application
COPY . .

# Generate optimized autoload files
RUN composer dump-autoload --optimize --no-dev

# Setup SQLite database and permissions
RUN mkdir -p database storage/framework/{sessions,views,cache} storage/logs bootstrap/cache \
    && touch database/database.sqlite \
    && chown -R www-data:www-data database storage bootstrap/cache \
    && chmod -R 775 database storage bootstrap/cache

EXPOSE 8080

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
