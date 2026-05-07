FROM laravelsail/php82-composer

RUN apt-get update \
    && apt-get install -y unzip libzip-dev libpng-dev libonig-dev libxml2-dev zip cron \
    && docker-php-ext-install pdo pdo_mysql 

RUN echo "post_max_size = 2100M" > /usr/local/etc/php/conf.d/uploads.ini && \
    echo "upload_max_filesize = 2000M" >> /usr/local/etc/php/conf.d/uploads.ini && \
    echo "max_execution_time = 600" >> /usr/local/etc/php/conf.d/uploads.ini && \
    echo "max_input_time = 600" >> /usr/local/etc/php/conf.d/uploads.ini && \
    echo "memory_limit = 512M" >> /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www

COPY . .

RUN echo "PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin\n\
* * * * * cd /var/www && /usr/local/bin/php artisan schedule:run >> /var/www/storage/logs/cron.log 2>&1" \
> /etc/cron.d/laravel-cron \
&& chmod 0644 /etc/cron.d/laravel-cron \
&& crontab /etc/cron.d/laravel-cron

RUN mkdir -p /var/www/storage/logs && chmod -R 777 /var/www/storage

CMD ["cron", "-f"]