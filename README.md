### Run project
Set up your environment to run docker

- build docker
```
- docker-compose up --build
```
- install application
```
- bin/console d:d:c
- bin/console doctrine:migrations:migrate
- bin/console doctrine:fixtures:load
```