cd /deploy/britannia &&

/bin/dd if=/dev/zero of=/var/swap.1 bs=1M count=1024
/sbin/mkswap /var/swap.1
/sbin/swapon /var/swap.1

docker-compose build
docker-compose run php composer install &&
docker-compose run php bin/console doctrine:schema:update --force &&
docker-compose run php bin/console doctrine:database:import dumps/britannia.sql &&
docker-compose run encore yarn install &&
docker-compose run encore yarn build &&
docker-compose up -d
