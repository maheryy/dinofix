FROM php:8.1-fpm

RUN apt-get update && \
    apt-get install -y --no-install-recommends libssl-dev zlib1g-dev curl git unzip netcat libxml2-dev libpq-dev libzip-dev && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install -j$(nproc) zip opcache intl pdo_pgsql pgsql && \
    docker-php-source delete

# Composer install
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Yarn install
RUN apt-get remove cmdtest && \
    curl https://deb.nodesource.com/setup_12.x | bash && \
    curl https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
    echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list && \
    apt-get update && apt-get install -y yarn && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Set default timezone
RUN echo 'date.timezone=Europe/Paris' > /usr/local/etc/php/conf.d/timezone.ini

WORKDIR /var/www/html