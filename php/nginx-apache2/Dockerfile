FROM ubuntu:16.04

RUN apt-get update && apt-get install -y \
      software-properties-common build-essential \
      vim curl supervisor wget libcurl4-openssl-dev \
      apache2 nginx
RUN LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php \
    && apt-get update && apt-get install -y \
      php5.6-fpm php5.6-cli php5.6-curl php5.6-dev \
      php7.0-fpm php7.0-cli php7.0-curl php7.0-dev \
      php7.1-fpm php7.1-cli php7.1-curl php7.1-dev \
      php7.2-fpm php7.2-cli php7.2-curl php7.2-dev \
      libapache2-mod-php7.3 php7.3-fpm php7.3-cli php7.3-curl php7.3-dev \
    && apt-get clean

RUN mkdir -p /run/php

ADD supervisord.conf /etc/supervisor/conf.d/supervisord.conf
# nginx config
ADD nginx-default-site /etc/nginx/sites-available/default
# Apache config
ADD apache-000-default.conf /etc/apache2/sites-available/000-default.conf
ADD apache-ports.conf /etc/apache2/ports.conf
# Default "html"
RUN rm -Rf /var/www/html/*
COPY ./html /var/www/html

# Extract ddtrace package contents and copy to /opt
# This does not run the post-install hook so ddtrace
# will not be installed out of the box
RUN mkdir -p /src/ddtrace && cd $_ \
    && wget https://github.com/DataDog/dd-trace-php/releases/download/0.35.0/datadog-php-tracer_0.35.0_amd64.deb \
    && dpkg-deb -R datadog-php-tracer_0.35.0_amd64.deb . \
    && cp -a ./opt/. /opt/

CMD ["/usr/bin/supervisord"]
