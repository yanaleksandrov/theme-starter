FROM wordpress:5.8.3-php7.4-apache

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        gosu \
    ; \
    rm -rf /var/lib/apt/lists/*; \
    pecl install xdebug; \
    rm -rf /tmp/pear; \
    docker-php-ext-enable xdebug

COPY *.ini $PHP_INI_DIR/conf.d/

COPY --chown=www-data:www-data composer.* /var/www/html/wp-content/

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN set -eux; \
	cd /var/www/html/wp-content/; \
    gosu www-data composer install --prefer-dist
