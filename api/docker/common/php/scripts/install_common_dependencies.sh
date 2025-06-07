#!/bin/bash

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

# Remove dependencies that are no longer needed (which are only needed to install extensions)
apt-get clean
apt-get remove -q -y \
    libicu-dev \
    libpq-dev \
    libzip-dev
