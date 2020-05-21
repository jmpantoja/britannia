cd /deploy/britannia &&
docker-compose exec php composer install &&
docker-compose exec php bin/console doctrine:database:create &&
docker-compose exec php bin/console doctrine:schema:udpate --force &&
docker-compose exec php bin/console doctrine:database:import /deploy/britannia/api/dumps/britannia.sql &&
docker-compose run encore yarn install &&
docker-compose run encore yarn build &&
docker-compose build
docker-compose up -d
