FROM php:8.2

RUN apt-get update -y &&\
    apt-get upgrade -y &&\
    apt-get install -y \
    curl \
    wget \
    unzip \
    sqlite3

RUN curl -sSLf \
        -o /usr/local/bin/install-php-extensions \
        https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions && \
    chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo_mysql bcmath zip gd pcntl exif opcache intl calendar redis sqlite3

RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - &&\
    apt-get install -y nodejs

RUN npm install --global yarn

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

RUN useradd -ms /bin/bash mzndesk && usermod -aG www-data mzndesk
RUN echo 'mzndesk:mzndesk' | chpasswd

WORKDIR /var/www
RUN rm -rf html
COPY ./ contactslist
RUN chown -R www-data:www-data ./contactslist
