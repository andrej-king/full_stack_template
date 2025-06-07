ARG PHP_VERSION=8.4
ARG XDEBUG_VERSION=3.4.2

FROM composer:2.8 AS composer

# =============== SYMFONY CLI IMAGE ===============
FROM --platform=$BUILDPLATFORM php:${PHP_VERSION}-cli-alpine3.21 AS symfony_cli

# Install symfony-cli
RUN <<EOF
    set -xe
    apk add --no-cache bash
    curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | /bin/bash
    apk add --no-cache symfony-cli
EOF

# =============== COMMON PHP-CLI IMAGE ===============
FROM --platform=$BUILDPLATFORM php:${PHP_VERSION}-cli-bookworm AS common_php_cli

ARG UID
ARG GID

WORKDIR /app

USER root

ENV ACCEPT_EULA=Y

# Replace default user / group id
RUN <<EOF
    set -xe
    groupmod --gid=${GID} www-data || true
    usermod --uid=${UID} --gid=${GID} www-data
    chown www-data /app /var/www
EOF

# Install common dependencies
RUN <<EOF
    set -xe
    apt-get update
    apt-get install --no-install-recommends --no-install-suggests -q -y \
        gnupg2 \
        libicu-dev \
        libpq-dev \
        libzip-dev \
        postgresql-client \
        unzip \
        openssl \
        $PHPIZE_DEPS
    docker-php-ext-enable \
        opcache
    docker-php-ext-configure \
        pgsql -with-pgsql=/usr/local/pgsql
    docker-php-ext-install \
        intl \
        pdo_pgsql \
        zip \
        bcmath
    # Remove dependencies that are no longer needed
    apt-get clean
    apt-get remove -q -y \
        libicu-dev \
        libpq-dev \
        libzip-dev
EOF

# Clean redundant files
RUN <<EOF
    set -xe
    apt-get update
    apt-get clean
    apt-get remove -q -y \
        $PHPIZE_DEPS
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
EOF

# Copy common config files
COPY ./docker/common/php/php-fpm.d /usr/local/etc/php-fpm.d
COPY ./docker/common/php/conf.d $PHP_INI_DIR/conf.d

COPY ./docker/common/wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod 555 /usr/local/bin/wait-for-it

USER www-data


# =============== COMMON PHP-FPM IMAGE ===============
FROM --platform=$BUILDPLATFORM php:${PHP_VERSION}-fpm-bookworm AS common_php_fpm

ARG UID
ARG GID

WORKDIR /app

USER root

ENV ACCEPT_EULA=Y

# Replace default user / group id
RUN <<EOF
    set -xe
    groupmod --gid=${GID} www-data || true
    usermod --uid=${UID} --gid=${GID} www-data
    chown www-data /app /var/www
EOF

# Install common dependencies
RUN <<EOF
    set -xe
    apt-get update
    apt-get install --no-install-recommends --no-install-suggests -q -y \
        gnupg2 \
        libicu-dev \
        libpq-dev \
        libzip-dev \
        postgresql-client \
        unzip \
        openssl \
        $PHPIZE_DEPS
    docker-php-ext-enable \
        opcache
    docker-php-ext-configure \
        pgsql -with-pgsql=/usr/local/pgsql
    docker-php-ext-install \
        intl \
        pdo_pgsql \
        zip \
        bcmath
    # Remove dependencies that are no longer needed
    apt-get clean
    apt-get remove -q -y \
        libicu-dev \
        libpq-dev \
        libzip-dev
EOF

# Clean redundant files
RUN <<EOF
    set -xe
    apt-get update
    apt-get clean
    apt-get remove -q -y \
        $PHPIZE_DEPS
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
EOF

# Copy common config files
COPY ./docker/common/php/php-fpm.d /usr/local/etc/php-fpm.d
COPY ./docker/common/php/conf.d $PHP_INI_DIR/conf.d

USER www-data


# =============== LOCAL DEV IMAGES ===============

# === CLI ===
FROM common_php_cli AS local_php_cli
ARG XDEBUG_VERSION

USER root

# Add composer with permissions
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Add symfony cli
COPY --from=symfony_cli /usr/bin/symfony /usr/bin/symfony

# Install xdebug and special apps
# Symfony recipes required git
RUN <<EOF
    set -xe
    apt-get update
    apt-get install --no-install-recommends --no-install-suggests -q -y \
        git \
        $PHPIZE_DEPS
    git clone --branch $XDEBUG_VERSION --depth 1 https://github.com/xdebug/xdebug.git /usr/src/php/ext/xdebug
    docker-php-ext-configure xdebug --enable-xdebug-dev
    docker-php-ext-install xdebug
    apt-get clean
    apt-get remove -q -y \
        $PHPIZE_DEPS
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
EOF

# Set dev php ini file as default
RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

# Copy config files
COPY ./docker/local/php/conf.d $PHP_INI_DIR/conf.d

USER www-data

# === FPM ===
FROM common_php_fpm AS local_php_fpm
ARG XDEBUG_VERSION

USER root

# Install xdebug and special apps
# Symfony recipes required git
RUN <<EOF
    set -xe
    apt-get update
    apt-get install --no-install-recommends --no-install-suggests -q -y \
        git \
        $PHPIZE_DEPS
    git clone --branch $XDEBUG_VERSION --depth 1 https://github.com/xdebug/xdebug.git /usr/src/php/ext/xdebug
    docker-php-ext-configure xdebug --enable-xdebug-dev
    docker-php-ext-install xdebug
    apt-get clean
    apt-get remove -q -y \
        $PHPIZE_DEPS
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
EOF

# Set dev php ini file as default
RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

# Copy config files
COPY ./docker/local/php/conf.d $PHP_INI_DIR/conf.d
COPY ./docker/local/php-fpm/conf.d $PHP_INI_DIR/conf.d

USER www-data


# =============== REMOTE DEV IMAGES ===============
# === CLI ===
FROM common_php_cli AS dev_php_cli
#RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
# TODO other ENV configs

# === FPM ===
FROM common_php_fpm AS dev_php_fpm
#RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
# TODO other ENV configs

# =============== PROD IMAGES ===============
# === CLI ===
FROM common_php_cli AS prod_php_cli
#RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
# TODO other ENV configs

# === FPM ===
FROM common_php_fpm AS prod_php_fpm
#RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
# TODO other ENV configs
