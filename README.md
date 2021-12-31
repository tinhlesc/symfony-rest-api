### Composer command
- composer create-project symfony/skeleton rest_api_demo
- composer require friendsofsymfony/rest-bundle
- composer require sensio/framework-extra-bundle
- composer require jms/serializer-bundle
- composer require symfony/validator
- composer require symfony/form
- composer require symfony/orm-pack
- composer require friendsofsymfony/user-bundle
- composer require friendsofsymfony/oauth-server-bundle
- composer require --dev symfony/test-pack 
- composer require --dev dama/doctrine-test-bundle
- composer require --dev doctrine/doctrine-fixtures-bundle

### Symfony command
- php bin/console make:migration
- php bin/console doctrine:migrations:migrate
- php bin/console make:entity --regenerate
- php bin/console --env=test doctrine:database:create
- php bin/console --env=test doctrine:schema:create
- php bin/console make:fixtures
- php bin/console doctrine:fixtures:load
- php bin/console cache:clear
- php bin/console doctrine:schema:create
- php bin/console doctrine:schema:update --force

### Docker command
- docker-compose up -d
- docker-compose down
- docker exec -it rest_api_demo_app_1 /bin/bash
- docker exec rest_api_demo_app_1 php bin/console cache:clear
- Rebuild docker: docker-compose up -d --force-recreate --build --remove-orphans

### Migration database
- docker exec rest_api_demo_app_1 php bin/console make:migration
- docker exec rest_api_demo_app_1 php bin/console doctrine:migrations:migrate

### Create Fos User
- docker exec -it rest_api_demo_app_1 /bin/bash
- php bin/console fos:user:create admin

### Referent
- https://symfony.com/doc/current/testing.html
- https://symfony.com/doc/3.3/doctrine.html