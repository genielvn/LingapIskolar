FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    procps \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY package*.json ./
COPY composer*.json ./
RUN composer install --no-scripts --no-autoloader
RUN npm install

COPY . .

RUN composer dump-autoload

EXPOSE 8000
EXPOSE 5173

CMD ["composer", "run", "dev"]