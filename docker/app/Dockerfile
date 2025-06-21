FROM php:8.2-fpm

# Instala dependências do sistema
RUN apt-get update \
  && apt-get install -y git zip unzip libzip-dev libonig-dev curl gnupg \
  && docker-php-ext-install pdo_mysql mbstring zip

# Instala Node.js e Yarn
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - \
  && apt-get install -y nodejs \
  && npm install -g yarn

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
