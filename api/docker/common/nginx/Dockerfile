#region Common
FROM --platform=$BUILDPLATFORM nginx:1.27-bookworm AS common_api_nginx

ARG UID
ARG GID

# Remove default config server block
#RUN rm /etc/nginx/conf.d/default.conf

# Copy common config files
#COPY ./docker/common/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/common/nginx/conf.d /etc/nginx/conf.d/
COPY ./docker/common/nginx/snippets /etc/nginx/snippets

WORKDIR /app

# Replace default user / group id
RUN <<EOF
    set -xe
    groupmod --gid=${GID} nginx || true
    usermod --uid=${UID} --gid=${GID} nginx
    chown -R nginx /app
EOF

# Set required nginx files access as non-root
RUN <<EOF
    touch /var/run/nginx.pid
    chown -R nginx \
        /var/cache/nginx  \
        /etc/nginx  \
        /var/log/nginx \
        /var/run/nginx.pid
EOF

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
