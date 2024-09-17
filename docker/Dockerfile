FROM php:8.2-fpm

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

COPY docker/start.sh /usr/local/bin/start

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
    nano \
    iputils-ping \
    mariadb-server \
    && docker-php-ext-install pdo_mysql exif pcntl bcmath gd intl zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# install mysql in THIS container only for running mysqldump command on remote db (in container DB)
# RUN apt-get update && apt-get install -y mariadb-server

#Install Node.js v16 & NPM
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash - \
    && apt-get update && apt-get install -y nodejs && npm install -g npm@9.2.0 \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Clear cache
# RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
# RUN docker-php-ext-install pdo_mysql exif pcntl bcmath gd intl zip

# Install composer
COPY --from=composer:2.5.1 /usr/bin/composer /usr/bin/composer

# Add user for laravel application && Add www-data user to www group
RUN groupadd -g 1000 www \
    && useradd -u 1000 -ms /bin/bash -g www www \
    && usermod -a -G www www-data

# make start.sh executable (if not)
RUN chmod u+x /usr/local/bin/start

# Copy existing application directory contents
# COPY . /var/www

# Copy existing application directory permissions
# COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000

# CMD ["php-fpm"]
CMD ["/usr/local/bin/start"]


