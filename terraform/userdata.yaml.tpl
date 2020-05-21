#cloud-config
apt_sources:
- source: "ppa:ansible/ansible" # Quote the string
package_update: true
packages:
  - docker.io
  - docker-compose
  - git
  - ansible
write_files:
  - path: /root/tmp/env.tmp
    content: |
        CONTAINER_REGISTRY_BASE=quay.io/api-platform

        MYSQL_USER=${mysql_user}
        MYSQL_ROOT_PASSWORD=${mysql_root_password}
        MYSQL_PASSWORD=${mysql_password}
        MYSQL_DATABASE=${mysql_database}

  - path: /root/tmp/docker-compose.override.yml.tmp
    content: |
        version: '3.4'
        services:
            php:
                environment:
                - APP_ENV=${app_env}

            proxy:
                environment:
                    DOMAINS: 'www.${app_url} -> http://cache-proxy:80 #production'

  - path: /root/tmp/api.env.local.tmp
    content: |
        SITE_BASE_URL=https://www.${app_url}

        ###> symfony/framework-bundle ###
        APP_ENV=${app_env}
        APP_SECRET=${app_secret}
        ###< symfony/framework-bundle ###

        ###> doctrine/doctrine-bundle ###
        DATABASE_URL=mysql://${mysql_user}:${mysql_password}@db/${mysql_database}
        #DATABASE_OLD_URL=mysql://${mysql_user}:${mysql_password}@db/academia_mysql
        ###< doctrine/doctrine-bundle ###

        ###> sms ###
        DESCOM_USER=${app_descom_user}
        DESCOM_PASSWORD=${app_descom_password}
        ###< sms ###

        ##> symfony/swiftmailer-bundle ###
        MANAGERS_MAILER_URL="${app_mailer_managers}"
        RECEPTION_MAILER_URL="${app_mailer_reception}"
        TEACHERS_MAILER_URL="${app_mailer_teachers}"
        ##< symfony/swiftmailer-bundle ###

  - path: /root/tmp/rclone.conf
    content: |
        [drive]
        type = drive
        client_id = 144684783967-mbq975pci7cj6t26jkgmsd1cp3kv2u5o.apps.googleusercontent.com
        client_secret = 7ZdMCYbAZusI8QD2c_-RGsMJ
        scope = drive
        token = {"access_token":"ya29.GltGBxWBr_SfWC9Iv3yD6aQVmYOPP6EEoaVTqWHOED2K_pRSQ-RYMNy40cx95i6oN9uSPapQ-6dfAkkMF0nfiV9owo7dCaSMKPmBMHMV-3c9SYOihBPon9H57pSl","token_type":"Bearer","refresh_token":"1/Gzkku9egiz9sY3qylDUtlFtR1cp10AKe98ha48JHiXY","expiry":"2019-07-15T14:26:12.436099409+02:00"}

        [GoogleDrive]
        type = drive
        scope = drive
        root_folder_id = 19-lVKLPeXHVq9xZkt7himSdHd9ijUoyn
        token = {"access_token":"ya29.a0AfH6SMD1J46TubcM-88K4Py4B8a3cXhQa8lZN-ALvTy11KTswGiUl7SaT2rTO4ATBoLy_QhpB11UaKP2MJWlCUuiDgFoiOU6ioQutngQ5abEebHnf9hEaSUMSwGN3dAs25kgBuEN0lHh1dQVjSZSWBowCnwszQuSowg","token_type":"Bearer","refresh_token":"1//03ea31rvmxOz_CgYIARAAGAMSNwF-L9Ir8kO54-ZFGzc91O4sZ-RTT5yDSJ39b8RyB2NiIq7JUZhhf2kDdV3t35pP2FSfBYEvl0g","expiry":"2020-05-20T16:03:35.622638908+02:00"}


runcmd:
  - mkdir /deploy
  - git config --global user.name "jmpantoja"
  - git config --global user.email "jmpantoja@gmail.com"
  - cd /deploy
  - git clone ${github_repository_url} britannia
  - cd /deploy/britannia
  - git checkout ${github_branch}
  - mv /root/tmp/env.tmp /deploy/britannia/.env
  - mv /root/tmp/docker-compose.override.yml.tmp /deploy/britannia/docker-compose.override.yml
  - mv /root/tmp/api.env.local.tmp /deploy/britannia/api/.env.local
  - mv /root/tmp/rclone.conf /deploy/britannia/api/docker/php/rclone.conf
  - mv /root/tmp/dumps /deploy/britannia/api/dumps
  - mv /root/tmp/uploads/attachments /deploy/britannia/api/uploads/attachments
  - mv /root/tmp/uploads/photos /deploy/britannia/api/uploads/photos
  - rm -rf /root/tmp/
