FROM php:7.4

RUN apt-get update -y && apt-get install -y openssl zip unzip git libonig-dev libpq-dev
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo mbstring pdo_mysql
# RUN pecl install redis && docker-php-ext-enable redis
RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis

WORKDIR /var/www/app
COPY . .
RUN composer install

CMD php artisan serve --host=0.0.0.0
EXPOSE 8000