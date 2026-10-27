# ---------- Etapa 1: compilar assets con Vite ----------
# Node 22 (LTS con soporte); Node 20 ya no recibe parches.
FROM node:22-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ---------- Etapa 2: imagen final PHP + Nginx ----------
FROM php:8.3-fpm-alpine

# Seguridad: actualiza paquetes del sistema y certificados raíz
# (retira los intermedios comprometidos de eMudhra).
RUN apk upgrade --no-cache \
    && apk add --no-cache ca-certificates \
    && update-ca-certificates

# Seguridad: exige PHP >= 8.3.36 (parche PHP2Shell). Si Render usa una
# imagen vieja en caché, el build falla aquí y la versión en línea sigue activa.
RUN php -r 'if (version_compare(PHP_VERSION, "8.3.36", "<")) { fwrite(STDERR, "PHP " . PHP_VERSION . " sin parche PHP2Shell: use Clear build cache & deploy\n"); exit(1); } echo "PHP " . PHP_VERSION . " OK\n";'

# Seguridad: desactiva session.upload_progress (vector de PHP2Shell; Laravel no lo usa).
RUN printf 'session.upload_progress.enabled = Off\nexpose_php = Off\n' > /usr/local/etc/php/conf.d/zz-seguridad.ini

RUN apk add --no-cache nginx bash gettext git unzip libpng-dev libzip-dev postgresql-dev oniguruma-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql mbstring zip gd bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .
COPY --from=assets /app/public/build ./public/build

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/nginx.conf.template /etc/nginx/nginx.conf.template
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 10000
CMD ["/start.sh"]
