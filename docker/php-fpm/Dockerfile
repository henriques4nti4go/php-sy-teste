FROM php:8.0-fpm
MAINTAINER Laurynas Sakalauskas <laurynas@sakalauskas.co.uk>

# Install PHP Dependencies
RUN \
  apt-get update && \
  apt-get install zip unzip git libxrender1 libfontconfig1 libpcre3-dev libbz2-dev  libfreetype6-dev libjpeg62-turbo-dev libpng-dev libxml2-dev libxslt-dev build-essential pkg-config libssl-dev -y && \
  pecl channel-update pecl.php.net && \
  docker-php-ext-configure gd --with-freetype --with-jpeg && \
  # docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
  docker-php-ext-install -j$(nproc) pcntl exif soap gd xsl bz2 bcmath 

RUN apt-get install -y libpq-dev \
  && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install pdo pdo_pgsql pgsql

# Install Composer
RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
  && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
  && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { echo 'Invalid installer' . PHP_EOL; exit(1); }" \
  && php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer \
  && php -r "unlink('/tmp/composer-setup.php');" \
  && php -r "unlink('/tmp/composer-setup.sig');"

# Install Symfony
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# Install node
# RUN curl -sL https://deb.nodesource.com/setup_12.x | bash - 
RUN apt install nodejs -y
RUN apt-get install npm -y


ENV PATH /composer/vendor/bin:$PATH

# COPY composer.* ./

# RUN composer install --no-scripts 

COPY . .

# WORKDIR /var/www/html/public

# RUN npm install

# WORKDIR /var/www/html



