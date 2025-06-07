ARG PHP_VERSION=8.4
ARG XDEBUG_VERSION=3.4.2
ARG COMPOSER_VERSION=2.8

FROM composer:${COMPOSER_VERSION} AS composer

#region Symfony CLI
FROM --platform=$BUILDPLATFORM php:${PHP_VERSION}-cli-bookworm AS symfony_cli
USER root
COPY --chmod=755 ./docker/common/php/scripts/install_symfony_cli.sh /setup.sh
RUN /bin/bash /setup.sh
#endregion

#region Common php-cli
FROM --platform=$BUILDPLATFORM php:${PHP_VERSION}-cli-bookworm AS common_php_cli

ARG UID
ARG GID
ENV ACCEPT_EULA=Y

WORKDIR /app

USER root

# Copy required files
COPY --chmod=555 ./docker/common/php/scripts /scripts
COPY --chmod=555 ./docker/common/wait-for-it.sh /usr/local/bin/wait-for-it

# Exec scripts
RUN /bin/bash /scripts/setup_user.sh                  # Replace default user / group id
RUN /bin/bash /scripts/install_common_dependencies.sh # Install common dependencies
RUN /bin/bash /scripts/clean_redundant_files.sh       # Clean redundant files

# Copy common config files
COPY ./docker/common/php/conf.d $PHP_INI_DIR/conf.d

USER www-data
#endregion

#region Common php-fpm
FROM --platform=$BUILDPLATFORM php:${PHP_VERSION}-fpm-bookworm AS common_php_fpm

ARG UID
ARG GID
ENV ACCEPT_EULA=Y

WORKDIR /app

USER root

# Copy required files
COPY --chmod=555 ./docker/common/php/scripts /scripts

# Exec scripts
RUN /bin/bash /scripts/setup_user.sh                  # Replace default user / group id
RUN /bin/bash /scripts/install_common_dependencies.sh # Install common dependencies
RUN /bin/bash /scripts/clean_redundant_files.sh       # Clean redundant files

# Copy common config files
COPY ./docker/common/php/php-fpm.d /usr/local/etc/php-fpm.d
COPY ./docker/common/php/conf.d $PHP_INI_DIR/conf.d

USER www-data
#endregion

#region Local php-cli
FROM common_php_cli AS local_php_cli
ARG XDEBUG_VERSION

USER root

# Add composer with permissions
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Add symfony cli
COPY --from=symfony_cli /usr/bin/symfony /usr/bin/symfony

# Exec scripts
RUN /bin/bash /scripts/install_local_env_dependencies.sh  # xdebug and special apps

# Set dev php ini file as default
RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

# Copy config files
COPY ./docker/local/php/conf.d $PHP_INI_DIR/conf.d

USER www-data
#endregion

#region Local php-fpm
FROM common_php_fpm AS local_php_fpm
ARG XDEBUG_VERSION

USER root

# Exec scripts
RUN /bin/bash /scripts/install_local_env_dependencies.sh  # xdebug and special apps

# Set dev php ini file as default
RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

# Copy config files
COPY ./docker/local/php/conf.d $PHP_INI_DIR/conf.d
COPY ./docker/local/php-fpm/conf.d $PHP_INI_DIR/conf.d

USER www-data
#endregion

#region Dev php-cli
FROM common_php_cli AS dev_php_cli
#RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
# TODO other ENV configs
#endregion

#region Dev php-fpm
FROM common_php_fpm AS dev_php_fpm
#RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
# TODO other ENV configs
#endregion

#region Prod php-cli
FROM common_php_cli AS prod_php_cli
#RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
# TODO other ENV configs
#endregion

#region Prod php-fpm
FROM common_php_fpm AS prod_php_fpm
#RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
# TODO other ENV configs
#endregion
