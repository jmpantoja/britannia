# the different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/compose/compose-file/#target

ARG PHP_VERSION=7.4.2
ARG NGINX_VERSION=1.15
ARG VARNISH_VERSION=6.0
ARG VER_PDFTK=2.02

FROM php:${PHP_VERSION}-fpm-buster AS api_platform_php

RUN useradd -m -o -u 1000 docker;

ENV PANTHER_NO_SANDBOX 1

ENV TZ=Europe/Madrid
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt update;
# persistent / runtime deps
RUN apt -y --fix-missing install \
#		acl \
#		file \
#		gettext \
		apt-utils \
		git \
		libssl-dev \
	;

RUN mkdir -p /usr/share/man/man1

#paquetes para generear pdfs
RUN apt update \
	&& apt install -y wget unzip pdftk \
	#imagemgick
	&& apt-get install -y libmagickwand-dev --no-install-recommends \
    && pecl install imagick && docker-php-ext-enable imagick \
    && printf "\n" | pecl install raphf propro && docker-php-ext-enable raphf propro \
#    && printf "\n" | pecl install pecl_http && docker-php-ext-enable pecl_http \
	&& apt clean ;

COPY docker/php/wkhtmltox_0.12.5-1.buster_amd64.deb ./wkhtmltox_0.12.5-1.buster_amd64.deb
RUN apt install -y ./wkhtmltox_0.12.5-1.buster_amd64.deb \
	&& rm ./wkhtmltox_0.12.5-1.buster_amd64.deb

#RUN wget https://github.com/wkhtmltopdf/wkhtmltopdf/releases/download/0.12.5/wkhtmltox_0.12.5-1.buster_amd64.deb \
#	&& apt install -y ./wkhtmltox_0.12.5-1.buster_amd64.deb \
#	&& rm ./wkhtmltox_0.12.5-1.buster_amd64.deb

ARG WITH_XDEBUG=false
RUN if [ $WITH_XDEBUG = "true" ] ; then \
	    pecl install xdebug; \
	    docker-php-ext-enable xdebug; \
	    echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
	    echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
	    echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
	    echo "xdebug.remote_autostart=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
	    echo "error_reporting=E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
	    echo "display_startup_err=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
	fi;

RUN apt update \
&& apt-get install -y cron;

COPY docker/php/crontab /srv/cron.crontab
RUN chmod 0644 /srv/cron.crontab
RUN crontab /srv/cron.crontab
RUN service cron start

#ARG APCU_VERSION=5.1.16
RUN set -eux; \
	apt -y install \
		$PHPIZE_DEPS \
		libicu-dev \
		libzip-dev  \
		zlib1g-dev  \
	;

RUN	docker-php-ext-install -j$(nproc) \
		intl \
#		pdo_pgsql \
		mysqli \
		pdo \
		pdo_mysql \
		zip \
		bcmath \
	;

#	pecl install \
#		apcu-${APCU_VERSION} \
#	; \
#	pecl clear-cache;
RUN	docker-php-ext-enable \
#		apcu \
		opcache \
		mysqli \
	;

#	\
#	runDeps="$( \
#		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
#			| tr ',' '\n' \
#			| sort -u \
#			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
#	)"; \
#	apk add --no-cache --virtual .api-phpexts-rundeps $runDeps; \
#	\
#	apk del .build-deps


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

COPY docker/php/conf.d/api-platform.ini $PHP_INI_DIR/conf.d/api-platform.ini

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN set -eux; \
	composer global require "hirak/prestissimo:^0.3" --prefer-dist --no-progress --no-suggest --classmap-authoritative; \
	composer clear-cache
ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /srv/api
#USER www-data

# build for production
ARG APP_ENV=prod

# prevent the reinstallation of vendors at every changes in the source code
COPY composer.json composer.lock symfony.lock ./
# do not use .env files in production
RUN echo '<?php return [];' > .env.local.php
#RUN set -eux; \
#	composer install --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress --no-suggest; \
#	composer clear-cache

# copy only specifically what we need
COPY bin bin/
COPY config config/
COPY public public/
COPY src src/

RUN set -eux; \
	mkdir -p var/cache var/log; \
	chmod 777 /var/www; \
#	composer dumps-autoload --classmap-authoritative --no-dev; \
#	composer run-script --no-dev post-install-cmd; \
	chmod +x bin/console; sync
VOLUME /srv/api/var

COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

FROM nginx:${NGINX_VERSION}-alpine AS api_platform_nginx

COPY docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf


WORKDIR /srv/api

COPY --from=api_platform_php /srv/api/public public/

FROM cooptilleuls/varnish:${VARNISH_VERSION}-alpine AS api_platform_varnish

COPY docker/varnish/conf/default.vcl /usr/local/etc/varnish/default.vcl

