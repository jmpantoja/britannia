#cloud-config
apt_sources:
- source: "ppa:ansible/ansible" # Quote the string
package_update: true
packages:
  - docker.io
  - docker-compose
  - git
  - ansible
  - composer
write_files:
  - path: /root/env.tmp
    content: |
        CONTAINER_REGISTRY_BASE=quay.io/api-platform

        MYSQL_USER=${mysql_user}
        MYSQL_ROOT_PASSWORD=${mysql_root_password}
        MYSQL_PASSWORD=${mysql_password}
        MYSQL_DATABASE=${mysql_database}

  - path: /root/docker-compose.override.yml.tmp
    content: |
        version: '3.4'
        services:
            proxy:
                environment:
                    DOMAINS: 'www.${app_url} -> http://cache-proxy:80 #local, ${app_url} -> http://cache-proxy:80 #local'

  - path: /root/api.env.local.tmp
    content: |
        SITE_BASE_URL=https://www.${app_url}

        ###> symfony/framework-bundle ###
        APP_ENV=dev
        APP_SECRET=${app_secret}
        ###< symfony/framework-bundle ###

        ###> doctrine/doctrine-bundle ###
        DATABASE_URL=mysql://${mysql_user}:${mysql_password}@db/${mysql_database}
        #DATABASE_OLD_URL=mysql://${mysql_user}:${mysql_password}@db/academia_mysql
        ###< doctrine/doctrine-bundle ###


runcmd:
  - mkdir /deploy
  - git config --global user.name "jmpantoja"
  - git config --global user.email "jmpantoja@gmail.com"
  - cd /deploy
  - git clone ${github_repository_url} britannia
  - git checkout ${github_branch}
  - mv /root/env.tmp /deploy/britannia/.env
  - mv /root/docker-compose.override.yml.tmp /deploy/britannia/docker-compose.override.yml
  - mv /root/api.env.local.tmp /deploy/britannia/api/.env.local
  - cd /deploy/britannia/api
  - composer install
  - cd /deploy/britannia
  - docker-compose up -d
