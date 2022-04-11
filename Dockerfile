From trafex/php-nginx
COPY nginx.conf /etc/nginx/nginx.conf
COPY custom.ini /etc/php8/conf.d/custom.ini
WORKDIR /web-security
