FROM php:8.4-cli

RUN docker-php-ext-install pdo pdo_mysql \
    && echo "post_max_size = 500M\nupload_max_filesize = 500M" > /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /app

COPY . .

CMD ["php", "-S", "0.0.0.0:8000"]
