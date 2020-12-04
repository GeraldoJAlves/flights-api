#!/bin/sh

if [ -z "$(ls -A vendor)"]; then
  cp .env.local .env
  echo "Instalando dependencias"
  composer install --no-progress --no-suggest -q --no-interaction
  echo "Dependencias instaladas"
fi

cd /var/www/html/app/public
php-fpm