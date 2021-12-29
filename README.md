## docker-compose

add alias to bash.bashrc for docker-compose

alias docker-compose='/usr/local/bin/docker-compose'

create Dockerfile and docker-compose.yaml

docker-compose.yaml - container setup

docker ps - see all running containers

docker exec -it --user www-data container_name sh - work in container terminal with user www-data

## laravel passport

https://laravel.com/docs/7.x/passport

composer require laravel/passport "~9.0"

php artisan passport:install

php artisan migrate

https://stackoverflow.com/questions/55168808/passport-laravel-createtoken-personal-access-client-not-found


## make resourse

php artisan make:controller RoleController --api - (--api create all resourse methods)


\DB::beginTransaction(); 
\DB::commit();

if we want to not insert data if have error


## for mailcatcher

https://mailcatcher.me/

sudo apt install ruby-full

gem install mailcatcher

mailcatcher

Go to http://127.0.0.1:1080/

Send mail through smtp://127.0.0.1:1025










composer require --dev barryvdh/laravel-ide-helper 2.8

php artisan ide:generate

php artisan ide:models