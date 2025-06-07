#!/bin/bash

set -xe
groupmod --gid=${GID} nginx || true
usermod --uid=${UID} --gid=${GID} nginx
chown -R nginx /app
