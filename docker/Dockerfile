FROM debian:buster-slim
RUN apt-get update \
 && apt-get install -y \
    php7.3-fpm \
    php7.3-intl \
    php7.3-curl \
    php7.3-mysql \
    nginx-light \
    ssl-cert

EXPOSE 8080
RUN mkdir -p /run/nginx
RUN mkdir -p /run/php

COPY ansible/roles/nginx/files/default /etc/nginx/sites-enabled/default
COPY public                            /var/www/html/public
COPY application                       /var/www/html/application
COPY docker/env                        /var/www/html/application/.env

RUN chown -R www-data:www-data /var/www/html/
RUN chmod +w \
    /var/www/html/application/writable/debugbar \
    /var/www/html/application/writable/cache \
    /var/www/html/application/writable/uploads \
    /var/www/html/application/writable/logs \
    /var/www/html/application/writable/session

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

RUN echo "daemon off;" >> /etc/nginx/nginx.conf
CMD ["nginx"]
