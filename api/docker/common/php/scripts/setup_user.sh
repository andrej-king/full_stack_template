#!/bin/bash

set -xe
groupmod --gid=${GID} www-data || true
usermod --uid=${UID} --gid=${GID} www-data
chown www-data /app /var/www
