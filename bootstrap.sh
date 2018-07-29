#!/usr/bin/env bash

apt-get update
# Install apache2, php and all necesarry modules
apt-get install -y apache2 libapache2-mod-php5 php-apc php5-cli php5-common php5-gd php5-mcrypt php5-mysql php5-mhash php5-curl php5-intl php5-xsl vim mysql-client ntp git php5-dev php5-imagick php5-xdebug

# Instal xdebug and enable it in php
# pecl install xdebug -y

XDEBUGINI=$(cat <<EOF
zend_extension=/usr/lib/php5/20131226/xdebug.so
xdebug.max_nesting_level=400
xdebug.remote_enable=1
xdebug.remote_autostart=1
xdebug.remote_connect_back=1
html_errors=1
xdebug.extended_info=1
EOF
)

if ! [ -L /var/www ]; then
  rm -rf /var/www
  ln -fs /vagrant /var/www
fi