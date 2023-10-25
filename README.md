# Symfony-api-docker
````
symfony console make:controller ControllerName --no-template
symfony serve
php bin/console doctrine:fixtures:load
php bin/console --env=test doctrine:fixtures:load

````
# Utils
```
vendor/bin/phpstan analyse src tests
php bin/phpunit
```