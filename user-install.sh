#!/usr/bin/env bash

ADMIN_EMAIL='mgargano@gmail.com'
SITE='sw'
LOG_FILE='/home/vagrant/user-install.log'
PROD_URL='testsite.com'
DEV_URL='sw.dev'
DIRECTORY="/var/www/$SITE"
ROOT_DIRECTORY='/vagrant'


trap ctrl_c INT
ctrl_c() {
  tput bold >&3; tput setaf 1 >&3; echo -e '\nCancelled by user' >&3; echo -e '\nCancelled by user'; tput sgr0 >&3; if [ -n "$!" ]; then kill $!; fi; exit 1
}

log2file() {
  exec 3>&1 4>&2
  trap 'exec 2>&4 1>&3' 0 1 2 3
  exec 1>${LOG_FILE} 2>&1
}

log2file

echo "---- run user scripts ----" >&3
cd /vagrant
if [ ! -f /vagrant/wp-config.php ]; then

  echo "---- set up wordpress the way we want it done ----" >&3
  ssh-keyscan bitbucket.org >> ~/.ssh/known_hosts
  ssh-keyscan github.com >> ~/.ssh/known_hosts
  composer update
  rm  /vagrant/wp/wp-config.php
  rm  /vagrant/wp/index.php


  wp plugin activate wp-migrate-db-pro wp-migrate-db-pro-media-files wp-migrate-db-pro-cli wp-cli-migrate-db-pro --path="/vagrant/wp"
  mysql -h localhost -uroot -proot $SITE -e "update wp_options set option_value='http://$DEV_URL/wp' where option_id=1 limit 1;"
  mysql -h localhost -uroot -proot $SITE -e "update wp_options set option_value='http://$DEV_URL' where option_id=2 limit 1;"
  cd /vagrant
  wp theme activate $SITE

  echo "---- globally install phpunit ----" >&3
  composer global require "phpunit/phpunit=4.2.*"
  
  
fi

echo "---- done with vagrant user stuff, logged to ${LOG_FILE} ----" >&3
