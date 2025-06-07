#!/bin/bash

# Install xdebug and special apps
# Symfony recipes required git
set -xe
apt-get update
apt-get install --no-install-recommends --no-install-suggests -q -y \
    git \
    $PHPIZE_DEPS
git clone --branch ${XDEBUG_VERSION} --depth 1 https://github.com/xdebug/xdebug.git /usr/src/php/ext/xdebug
docker-php-ext-configure xdebug --enable-xdebug-dev
docker-php-ext-install xdebug

apt-get clean
apt-get remove -q -y \
    $PHPIZE_DEPS
rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
