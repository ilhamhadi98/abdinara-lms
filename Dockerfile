# Node dependencies stage for building Vite assets
FROM node:20 AS node_builder
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm install
COPY . .
RUN npm run build

# PHP App stage
FROM php:8.4-cli

# Install system dependencies + PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql zip pcntl sockets opcache intl gd \
    && rm -rf /var/lib/apt/lists/*

# OPcache tuning untuk Octane/RoadRunner (long-running process)
RUN { \
    echo "opcache.enable=1"; \
    echo "opcache.enable_cli=1"; \
    echo "opcache.memory_consumption=256"; \
    echo "opcache.interned_strings_buffer=16"; \
    echo "opcache.max_accelerated_files=20000"; \
    echo "opcache.validate_timestamps=0"; \
    echo "opcache.save_comments=1"; \
    echo "opcache.fast_shutdown=1"; \
    echo "opcache.jit=tracing"; \
    echo "opcache.jit_buffer_size=128M"; \
    } > /usr/local/etc/php/conf.d/opcache.ini

# Realpath cache untuk kurangi syscall filesystem
RUN { \
    echo "realpath_cache_size=4096K"; \
    echo "realpath_cache_ttl=600"; \
    } > /usr/local/etc/php/conf.d/realpath.ini

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy semua source code ke dalam container
# (vendor/, node_modules/, storage/logs/, bootstrap/cache/ sudah di-.dockerignore)
COPY . .

# Instal PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Salin pre-compiled Vite assets dari stage pertama Node.js
COPY --from=node_builder /app/public/build /var/www/html/public/build

# Buat direktori storage 
RUN mkdir -p storage/logs \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache public/build

EXPOSE 8000
