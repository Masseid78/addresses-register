@'
FROM php:8.2-fpm

# Instala extensões
RUN apt-get update && apt-get install -y `
    git zip unzip libzip-dev libonig-dev `
  && docker-php-ext-install pdo_mysql mbstring zip

# Composer já disponível
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
'@ > docker/app/Dockerfile
