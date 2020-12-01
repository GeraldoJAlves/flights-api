#!/bin/bash

echo Uploading Application container 
docker-compose up --build -d

echo Install dependencies
docker-compose exec php "composer install"

echo Make migrations
docker-compose exec php php /var/www/html/artisan migrate

echo Information of new containers
docker ps -a 