# Use PHP 8.2 FPM as the base image
FROM php:8.2-fpm

# Define user arguments (can be overridden during build)
ARG user=app
ARG uid=1000

# Install system dependencies including Node.js and npm
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && curl -sL https://deb.nodesource.com/setup_16.x | bash - && \
    apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    sockets

# Install Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /tmp/pear

# Add Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create a non-root user for running Composer and Artisan commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user && \
    mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set the working directory
WORKDIR /var/www

# Copy the application code
COPY . /var/www

# Switch to the non-root user
USER $user

# Run artisan key generation and npm build commands
RUN php artisan key:generate && \
    npm install && \
    npm run build

# Expose the necessary port (adjust as needed)
EXPOSE 9000


