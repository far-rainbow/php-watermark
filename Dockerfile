FROM php:7.4-cli-alpine
# @see https://hub.docker.com/r/jpswade/php7.4-fpm-alpine
MAINTAINER Agent Software <dev@agentsoftware.net>

# Install gd, iconv, mbstring, mysql, soap, sockets, zip, and zlib extensions
# see example at https://hub.docker.com/_/php/
RUN apk add --update \
                $PHPIZE_DEPS \
                freetype-dev \
                libjpeg-turbo-dev \
                libpng-dev \
                php7-session \
                imagemagick \
                imagemagick-libs \
                imagemagick-dev \
                php7-imagick \

        && docker-php-ext-install iconv exif \
        && docker-php-ext-configure gd --with-jpeg --with-freetype \
        && docker-php-ext-install gd

RUN printf "\n" | pecl install \
                imagick && \
                docker-php-ext-enable --ini-name 20-imagick.ini imagick

COPY . /app
WORKDIR /app
CMD [ "php", "wm.php" ]
