#!/usr/bin/env bash

CONTAINER_NAME="cobrancaxml.php-app"
if [[ ! $(docker ps -q -f name=${CONTAINER_NAME}) ]]; then
  docker-compose up -d
fi
docker exec ${CONTAINER_NAME} vendor/bin/phpunit --colors=always

docker-compose down