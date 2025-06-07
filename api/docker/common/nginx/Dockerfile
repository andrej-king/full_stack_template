#region Common
FROM --platform=$BUILDPLATFORM nginx:1.27-bookworm AS common_api_nginx

ARG UID
ARG GID

# Copy common config files
COPY ./docker/common/nginx/conf.d /etc/nginx/conf.d/
COPY ./docker/common/nginx/snippets /etc/nginx/snippets

WORKDIR /app

# Copy required files
COPY --chmod=555 ./docker/common/nginx/scripts /scripts

# Exec scripts
RUN /bin/bash /scripts/setup_user.sh        # Replace default user / group id
RUN /bin/bash /scripts/files_permissions.sh # Set required nginx files access as non-root

USER nginx

CMD ["nginx","-g","daemon off;"]
#endregion

#region Local
FROM common_api_nginx AS local_api_nginx
#endregion

#region Dev
FROM common_api_nginx AS dev_api_nginx
COPY --chown=nginx ./api/public ./public
#endregion

#region Prod
FROM common_api_nginx AS prod_api_nginx
COPY --chown=nginx ./api/public ./public
#endregion
