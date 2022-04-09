#!/bin/bash
. ./.env
docker-compose up --build &
until PGPASSWORD=$POSTGRES_PASSWORD psql -h ${POSTGRES_DB_HOST} -p ${DATABASE_PORT} -U ${POSTGRES_USER} ${POSTGRES_DB} -c '\q'; do
  >&2 echo "Postgres is unavailable - sleeping"
  sleep 1
done
echo "DB running"
php bin/console --no-interaction make:migration
php bin/console --no-interaction doctrine:migrations:migrate
symfony server:start -d
sh ./tests/game_test.sh
symfony server:stop
docker-compose down