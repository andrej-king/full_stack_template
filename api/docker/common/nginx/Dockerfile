FROM --platform=$BUILDPLATFORM nginx:1.27-bookworm AS common_nginx

ARG UID
ARG GID

# Remove default config server block
RUN rm /etc/nginx/conf.d/default.conf

# Copy common config files
COPY ./docker/common/nginx/nginx.conf /etc/nginx/nginx.conf

# Copy app common config files
COPY ./api/docker/common/nginx/snippets /etc/nginx/snippets
COPY ./api/docker/common/nginx/conf.d /etc/nginx/conf.d/

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


# ============================================= LOCAL DEV IMAGE =============================================
FROM common_nginx AS local_nginx

# Copy local config files
#COPY ./api/docker/local/nginx/conf.d/ /etc/nginx/conf.d/
