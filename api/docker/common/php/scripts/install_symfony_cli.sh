#!/bin/bash

set -xe
apt-get update
apt-get install -y curl bash gnupg ca-certificates
curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | /bin/bash
apt-get install -y symfony-cli
