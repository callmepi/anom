FROM php:8.1-apache as builder

# ARG MODE="PRODUCTION"
ARG MODE="debug"


# mailhog (fake mail) configuration
# ------------------------------------------------------------------------------
# RUN apt-get update &&\
#     apt-get install --no-install-recommends --assume-yes --quiet ca-certificates curl git &&\
#     rm -rf /var/lib/apt/lists/*
# RUN curl -Lsf 'https://storage.googleapis.com/golang/go1.8.3.linux-amd64.tar.gz' | tar -C '/usr/local' -xvzf -
# ENV PATH /usr/local/go/bin:$PATH
# RUN go get github.com/mailhog/mhsendmail
# RUN cp /root/go/bin/mhsendmail /usr/bin/mhsendmail
# RUN echo 'sendmail_path = /usr/bin/mhsendmail --smtp-addr mailhog:1025' > /usr/local/etc/php/php.ini


# msmtp (real mail) configuration
# ------------------------------------------------------------------------------
# RUN apt-get update && apt-get install -y msmtp && apt-get clean && rm -r /var/lib/apt/lists/*
# configure sendmail
# RUN { \
#     	echo "php_admin_value[sendmail_path] = $(which msmtp) -ti "; \
#     } >> /usr/local/etc/php-fpm.d/www.conf


# setup apache
# ------------------------------------------------------------------------------

# enable apache mod_rewrite and headers
RUN a2enmod rewrite
RUN a2enmod headers

# install pdo and mysql for php
RUN docker-php-ext-install pdo pdo_mysql mysqli

# copy application code
# ------------------------------------------------------------------------------

# Copy local code to the container image.
COPY public /var/www/public

# Copy core files behind public
COPY core /var/www/core

# Copy storage folder structure behind public
COPY storage /var/www/storage

# Copy configuration files for the container (application based)
COPY .env /var/www/
COPY docker /var/www/docker
COPY tests /var/www/tests
COPY composer.json /var/www/


# Install Composer
# ------------------------------------------------------------------------------

# Install Composer with required libraries
RUN apt-get update --fix-missing
RUN DEBIAN_FRONTEND=noninteractive apt-get install git unzip wget -y
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm composer-setup.php


# Add Greek locales to server
# ------------------------------------------------------------------------------
WORKDIR /
RUN apt-get install -y locales
RUN sed -i 's/^# *\(el_GR\)/\1/' /etc/locale.gen
RUN locale-gen


# Manage permitions in specific (writable) directories
# ------------------------------------------------------------------------------
# (make local storage directories writable)
RUN chown -R www-data:www-data /var/www
RUN chmod -R 757 /var/www/storage
RUN chown -R www-data:www-data /var/www/public/files
RUN chmod -R 757 /var/www/public/files

# change apache's default-root to /var/www/public
RUN sed -ri -e 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/*.conf


# Use the PORT environment variable in Apache configuration files.
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Override with custom settings
# TODO: COPY config/php-ini-overrides.ini $PHP_INI_DIR/conf.d/


# Instal Memcached
# ------------------------------------------------------------------------------
# RUN apt-get update && apt-get install -y libmemcached-dev zlib1g-dev && pecl install memcached-3.2.0 && docker-php-ext-enable memcached
# CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]


# Install and setup cron
# ------------------------------------------------------------------------------
# RUN apt-get update && apt-get install cron -y 
# RUN crontab -l | { cat; echo "10 * * * * /usr/local/bin/php /var/www/core/cli/cache-categories.php"; } | crontab -
# or? RUN crontab -l | { cat; echo "10 * * * * php /var/www/core/cli/cache-categories.php"; } | crontab -
# ... or copy cronjobs file into/as /etc/cron.d/cron ; then RUN crontab /etc/cron.d/cron
# ... do that while on entrypoint
# (+) run once after ex. 4 minutes 


# Install XDebug (only for develop/debud modes)
# ------------------------------------------------------------------------------
# RUN apt-get install -y apt-utils \
#     && pecl install xdebug \
#     && docker-php-ext-enable xdebug
# COPY docker/config/php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
# RUN touch /usr/local/etc/php/xdebug.log
# RUN chown www-data:www-data /usr/local/etc/php/xdebug.log
# RUN chmod 664 /usr/local/etc/php/xdebug.log


# Prepare the entrypoint as in the original image
# ------------------------------------------------------------------------------
RUN cp /var/www/docker/bin/docker-entrypoint.sh /usr/local/bin
RUN chmod a+x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT []


# TODO:
## Enable OPcache and JIT on production
# ------------------------------------------------------------------------------
## set environemt variable

# RUN load OPcache and JIT from script
### if [ "${MODE}" = "PRODUCTION" ]; then
### docker-php-ext-install opcache
### docker-php-ext-install opcache
### mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

### chk: https://stackoverflow.com/questions/68308209/docker-compose-failed-to-solve-rpc-error-code-unknown-desc-failed-to-comp

# TODO:
# Enable JIT

### fi



# TODO:
## Enable OPcache and JIT on production
# ------------------------------------------------------------------------------
### optimize.sh
### #!/bin/bash -x
### 
### if test ["$1" == "PRODUCTION"] ; then 
###     docker-php-ext-install opcache
###     docker-php-ext-install opcache
###     install and enable JIT
### else 
###     maybe install some dev-tool
### fi
