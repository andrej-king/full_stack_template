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
    targets = [ "api-nginx" ]
}

group "prod" {
    targets = [ "api-nginx" ]
}

target "_common" {
    context    = "."
    no-cache = equal(0, USE_DOCKER_CACHE)
    args = {
        UID                     = UID
        GID                     = GID
        DOCKER_DEFAULT_PLATFORM = "${DOCKER_DEFAULT_PLATFORM}"
    }
}

target "api-nginx" {
    inherits = [ "_common" ]
    dockerfile = "docker/common/nginx/Dockerfile"
    target     = "${APP_ENV}_api_nginx"
    tags = [
        "${REGISTRY}/${APP_ENV}-api-nginx:${IMAGE_TAG}",
        "${REGISTRY}/${APP_ENV}-api-nginx:latest",
    ]
    # cache-from = [ "type=registry,ref=${REGISTRY}/${APP_ENV}-common-nginx:cache" ]
}
