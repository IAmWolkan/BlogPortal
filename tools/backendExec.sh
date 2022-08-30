#!/bin/sh

docker-compose exec -w /app/backend tools bash -c "$*"
