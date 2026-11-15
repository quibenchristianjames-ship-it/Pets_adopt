FROM php:8.2-apache
COPY . /var/www/html/
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN echo "HOST=$HOST" > /var/www/html/.env && \
    echo "DB=$DB" >> /var/www/html/.env && \
    echo "USER=$USER" >> /var/www/html/.env && \
    echo "PASS=$PASS" >> /var/www/html/.env
EXPOSE 80
