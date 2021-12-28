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