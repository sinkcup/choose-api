FROM php:apache
MAINTAINER sinkcup <sinkcup@163.com>

RUN apt-get update -qq && \
  apt-get upgrade -y && \
  apt-get install -y wget zlib1g-dev
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install zip
RUN cd /usr/local/bin/ && \
  curl -sS https://getcomposer.org/installer | php && \
  ln -s composer.phar composer
RUN ln -s ../mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load

ADD . /var/www/choose-api
RUN cd /var/www/choose-api && \
  composer update
RUN cd /var/www/choose-api/model/dao/db/inc/ && \
  php mysql_auto_process.php
ADD apache2/sites-enabled/ /etc/apache2/sites-enabled/
