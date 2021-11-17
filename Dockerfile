FROM php:8.0-fpm

ENV NODE_VERSION=16

# Copy composer.lock and composer.json
COPY backend/composer.lock backend/composer.json /var/www/

# Set working directory
WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libzip-dev \
    unzip \
    sudo \
    apt-utils \
    nano

# Install extensions
RUN docker-php-ext-install pdo_mysql exif pcntl bcmath gd intl zip

# Install composer
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

# Install node
# RUN curl -sL https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - && apt-get install -y nodejs

# Clear caches & temps
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Add group/user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Add www-data user to www group
RUN usermod -a -G www www-data

# Copy existing application directory contents
COPY ./backend /var/www

# Copy existing application directory permissions
COPY --chown=www:www ./backend /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
