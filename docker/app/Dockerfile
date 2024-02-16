FROM php:7.4-fpm

# Install modules
RUN apt-get update && \
    apt-get -y install \
        gnupg2 && \
    apt-key update && \
    apt-get update && \
    apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libicu-dev \
        wget \
        git \
        curl \
        imagemagick \
        libcurl3-dev \
        libicu-dev \
        libfreetype6-dev \
        libjpeg-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
        libmagickwand-dev \
        libpq-dev \
        libpng-dev \
        libxml2-dev \
        libzip-dev \
        libgmp-dev \
        zlib1g-dev \
        default-mysql-client \
        openssh-client \
        nano \
        mc \
        unzip \
        openssl \
        libcurl4-openssl-dev \
        libssl-dev \
        libmcrypt-dev \
        librabbitmq-dev \
        libssh-dev \
            --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev

RUN pecl install mcrypt-1.0.3 \
      imagick

RUN docker-php-ext-install zip intl mbstring pdo_mysql exif \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install gmp \
    && docker-php-ext-install bz2 \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install soap \
    && docker-php-ext-install sockets \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install calendar \
    && docker-php-ext-install ftp \
    && docker-php-ext-enable imagick

# Copy install-composer
COPY ./install-composer.sh /
RUN chmod +x /install-composer.sh

COPY ./php.ini /usr/local/etc/php/
COPY ./www.conf /usr/local/etc/php/

# Copy entrypoint script
COPY ./entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

RUN apt-get purge -y g++ \
    && apt-get autoremove -y \
    && apt-get clean \
    && rm -r /var/lib/apt/lists/* \
    && rm -rf /tmp/* \
    && rm -rf /var/tmp/* \
    && sh /install-composer.sh \
    && rm /install-composer.sh

# Install Yii framework bash autocompletion
RUN curl -L https://raw.githubusercontent.com/yiisoft/yii2/master/contrib/completion/bash/yii \
        -o /etc/bash_completion.d/yii


## Setup time zone Europe/Kiev
RUN rm -rf /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Kiev /etc/localtime

ENV UID 1000
ENV GID www-data

RUN chmod 777 -R /var/www/
RUN usermod -u $UID $GID

VOLUME /var/www/.composer
WORKDIR /app

EXPOSE 9000
CMD ["php-fpm"]

ENTRYPOINT ["/entrypoint.sh"]
