#!/bin/bash

echo Uploading Application container 
docker-compose up --build -d

echo Information of new containers
docker ps -a 