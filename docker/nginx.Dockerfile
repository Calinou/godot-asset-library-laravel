FROM nginx:1.19-alpine

WORKDIR /app/public

COPY ./docker/nginx.conf /etc/nginx/nginx.conf
COPY ./public/* /app/public/
