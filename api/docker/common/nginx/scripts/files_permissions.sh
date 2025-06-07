#!/bin/bash

set -xe
touch /var/run/nginx.pid
chown -R nginx \
    /var/cache/nginx  \
    /etc/nginx  \
    /var/log/nginx \
    /var/run/nginx.pid
