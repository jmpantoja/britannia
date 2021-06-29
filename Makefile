dev:
	docker-compose -f docker-compose.yml -f docker/docker-compose.dev.yml up -d --remove-orphans

pro:
	docker-compose -f docker-compose.yml up -d --remove-orphans

down:
	docker-compose down --remove-orphans

build:
	docker-compose -f docker/docker-compose.build.yml pull --ignore-pull-failures
	docker-compose -f docker/docker-compose.build.yml build --pull

push:
	docker-compose -f docker/docker-compose.build.yml push php

