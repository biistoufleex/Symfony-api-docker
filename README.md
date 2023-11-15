# Symfony-api-docker
````
symfony console make:controller ControllerName --no-template
symfony serve

# setup databases
# change database url dans les .env en fonction de local ou docker
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
php bin/console --env=test doctrine:database:create
php bin/console --env=test doctrine:schema:create
php bin/console --env=test doctrine:fixtures:load

docker run -d -p 1080:1080 -p 1025:1025 --name mailcatcher schickling/mailcatcher

lancer le server s3 en developement dans le dossier docker/aws

````
# Utils
```
vendor/bin/phpstan analyse src tests
php bin/phpunit
```

# Docker
```
docker-compose up -d
docker compose exec php bash
```

# Minio
```
docker-compose -f docker/aws/docker-compose.yml up -d
ce log avec les logs du docker compose
cree un bucket 'scansante'
cree des access keys et les mettre dans le .env.local
```
