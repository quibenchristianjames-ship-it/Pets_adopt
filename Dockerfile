FROM php:8.2-apache
COPY . /var/www/html/
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN printf "HOST=%s\nDB=%s\nUSER=%s\nPASS=%s\n" "$HOST" "$DB" "$USER" "$PASS" > /var/www/html/.env
EXPOSE 80
