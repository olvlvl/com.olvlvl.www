FROM php:8.3-apache-bookworm

#
# Debug
#

RUN <<-EOF
	apt-get update
	apt-get install -y autoconf pkg-config
	pecl channel-update pecl.php.net
	pecl install xdebug
	docker-php-ext-enable opcache xdebug
EOF

RUN <<-EOF
	cat <<-SHELL >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
	xdebug.client_host=host.docker.internal
	xdebug.mode=develop
	xdebug.start_with_request=yes
	xdebug.output_dir=/app/build/xdebug
	SHELL

	cat <<-SHELL >> /usr/local/etc/php/conf.d/php.ini
	display_errors=On
	error_reporting=E_ALL
	date.timezone=UTC
	SHELL
EOF

#
# Composer
#

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN <<-EOF
	apt-get update
	apt-get install unzip
	curl -s https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer | php -- --quiet
	mv composer.phar /usr/local/bin/composer
	cat <<-SHELL >> /root/.bashrc
	export PATH="$HOME/.composer/vendor/bin:$PATH"
	SHELL
EOF

#
# Web server
#

ENV APACHE_DOCUMENT_ROOT /app/web

RUN <<-EOF
	a2enmod rewrite
	sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
	sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
EOF
