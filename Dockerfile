FROM php:7.3-fpm

RUN useradd --user-group --shell /bin/false app

RUN apt-get update
RUN apt-get install -y libzip-dev curl git

RUN docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /code
ENV HOME=/code
WORKDIR $HOME

RUN printf '[www]\n\nuser=app\ngroup=app\n' >> /usr/local/etc/php-fpm.d/app-user.conf

USER root
COPY ./ $HOME
RUN chown -R app $HOME/*
RUN chmod -R 777 $HOME/*

USER app
