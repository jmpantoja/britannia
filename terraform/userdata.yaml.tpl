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
  - path: /root/env.local
    content: |
        SITE_BASE_URL=https://www.planb.ovh

        ###> symfony/framework-bundle ###
        APP_ENV=dev
        APP_SECRET=eZCmRupDXdcD
        ###< symfony/framework-bundle ###

        ###> doctrine/doctrine-bundle ###
        DATABASE_URL=mysql://britannia:Vq4q9HzEJ7VrHuZq@db/britannia
        DATABASE_OLD_URL=mysql://britannia:Vq4q9HzEJ7VrHuZq@db/academia_mysql
        ###< doctrine/doctrine-bundle ###

  - path: /root/index.html
    content: |
        sos un capo

runcmd:
  - docker run -d -v /root/index.html:/usr/share/nginx/html/index.html -p 80:80 nginx
#  - mkdir /deploy
#  - cd /deploy
#  - git config --global user.name "jmpantoja"
#  - git config --global user.email "jmpantoja@gmail.com"
#  - git clone ${github_repository_url} britannia
#  - cd /deploy/britannia
#  - ./deploy.sh master
#  - cp /root/env.tmp /deploy/britannia/.env
#  - docker-compose -f /deploy/britannia/docker-compose.yml up -d
