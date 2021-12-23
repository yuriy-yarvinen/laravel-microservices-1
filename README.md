add alias to bash.bashrc for docker-compose

alias docker-compose='/usr/local/bin/docker-compose'


create Dockerfile and docker-compose.yaml

docker-compose.yaml - container setup

docker ps - see all running containers

docker exec -it --user www-data container_name sh - work in container terminal with user www-data

https://laravel.com/docs/7.x/passport

composer require laravel/passport "~9.0"

php artisan migrate

php artisan passport:install

php artisan make:controller RoleController --api - (--api create all resourse methods)











composer require --dev barryvdh/laravel-ide-helper 2.8

php artisan ide:generate

php artisan ide:models