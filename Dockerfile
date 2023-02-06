FROM php:8.2-apache-buster

RUN apt-get update && \
	apt-get install -y autoconf pkg-config && \
	pecl channel-update pecl.php.net && \
	pecl install xdebug && \
	docker-php-ext-enable opcache xdebug

RUN a2enmod rewrite

RUN echo '\
xdebug.client_host=host.docker.internal\n\
xdebug.mode=off\n\
xdebug.start_with_request=yes\n\
' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN echo '\
display_errors=On\n\
error_reporting=E_ALL\n\
date.timezone=UTC\n\
' >> /usr/local/etc/php/conf.d/php.ini

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN apt-get update && \
	apt-get install unzip && \
    curl -s https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer | php -- --quiet && \
	mv composer.phar /usr/local/bin/composer

ENV APACHE_DOCUMENT_ROOT /app/web

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
