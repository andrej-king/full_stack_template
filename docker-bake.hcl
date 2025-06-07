# common config file for build docker images

variable "APP_ENV" { default = "local" }
variable "REGISTRY" { default = "localhost" }
variable "IMAGE_TAG" { default = "latest" }
variable "USE_DOCKER_CACHE" { default = 1 }
variable "DOCKER_DEFAULT_PLATFORM" { default = "linux/amd64" }
variable "UID" { default = 1000 }
variable "GID" { default = 1000 }

group "local" {
    targets = [ "api-nginx" ]
}

group "dev" {
    targets = [ "api-nginx", "api-php-fpm", "api-php-cli" ]
}

group "prod" {
    targets = [ "api-nginx", "api-php-fpm", "api-php-cli" ]
}

target "_common" {
    context = "."
    no-cache = equal(0, USE_DOCKER_CACHE)
    args = {
        UID                     = UID
        GID                     = GID
        DOCKER_DEFAULT_PLATFORM = "${DOCKER_DEFAULT_PLATFORM}"
    }
}

target "api-nginx" {
    inherits = [ "_common" ]
    context = "api"
    dockerfile = "docker/common/nginx/Dockerfile"
    target     = "${APP_ENV}_api_nginx"
    tags = [
        "${REGISTRY}/${APP_ENV}-api-nginx:${IMAGE_TAG}",
        "${REGISTRY}/${APP_ENV}-api-nginx:latest",
    ]
    #cache-from = equal("local", APP_ENV) ? [ ] : [ "type=registry,ref=${REGISTRY}/${APP_ENV}-common-nginx:cache" ]
}

target "api-php-fpm" {
    inherits = [ "_common" ]
    dockerfile = "docker/api/common/php/Dockerfile"
    target     = "${APP_ENV}_php_fpm"
    tags = [
        "${REGISTRY}/${APP_ENV}-php-fpm:${IMAGE_TAG}",
        "${REGISTRY}/${APP_ENV}-php-fpm:latest",
    ]
    #cache-from = equal("local", APP_ENV) ? [ ] : [ "type=registry,ref=${REGISTRY}/${APP_ENV}-common-php-fpm:cache" ]
}

target "api-php-cli" {
    inherits = [ "_common" ]
    dockerfile = "docker/api/common/php/Dockerfile"
    target     = "${APP_ENV}_php_cli"
    tags = [
        "${REGISTRY}/${APP_ENV}-php-cli:${IMAGE_TAG}",
        "${REGISTRY}/${APP_ENV}-php-cli:latest",
    ]
    #cache-from = equal("local", APP_ENV) ? [ ] : [ "type=registry,ref=${REGISTRY}/${APP_ENV}-common-php-cli:cache" ]
}
