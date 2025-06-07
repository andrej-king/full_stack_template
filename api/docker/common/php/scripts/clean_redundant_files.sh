#!/bin/bash

set -xe
apt-get update
apt-get clean
apt-get remove -q -y \
    $PHPIZE_DEPS
rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
