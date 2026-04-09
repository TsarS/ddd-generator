# Используем официальный образ PHP с CLI
FROM php:8.2-fpm

# Устанавливаем необходимые утилиты
RUN apt-get update \
      && apt-get -y --no-install-recommends install \
        libzip-dev \
        libicu-dev \
        libpq-dev \
        unzip \
        zip \
        git \
        curl \
        libfreetype-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        && rm -rf /var/lib/apt/lists/*

    # Configure and install the GD extension
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install -j$(nproc) gd

RUN apt-get update && apt-get install -y libzip-dev && docker-php-ext-install zip

RUN echo 'memory_limit = 1024M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

# Создаем рабочую директорию
WORKDIR /app

# Копируем остальные файлы проекта
COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

