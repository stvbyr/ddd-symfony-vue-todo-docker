# Symfony 5 Todo App

With this project I wanted to create a full stack project that uses a variety of different tools. First and foremost I wanted to explore a DDD approach for this project.

__This Project is a WIP. So big changes are very likely.__
## Stack

* Docker
* Nginx
* Symfony 5
* MariaDB
* Vue.js 3

## Installation

1. Clone the project

Wire up docker to get the development environment started
```bash
docker-compose up -d
```

Now you can access
* symfony backend: http://localhost:8080
* vue dev server: http://localhost:3000

For a production environment run (WIP)
```zsh
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
```

This runs `npm run build` on the vue image instead of booting up the dev server. It also sets the `APP_ENV=prod` so that the backend is started in production mode. The built vue frontend can then be accessed over: http://localhost:3001

Migrate doctrine entities and migrations
```
docker exec -it symfony /bin/bash
bin/console doctrine:migrations:migrate
```

Run Fixures. This will insert a demo "admin" user that you can use to test. Look [UserFixture](symfony/src/App/DataFixtures/UserFixture.php) for Details. It also sets up some demo data for the todos and habits.
```
docker exec -it symfony /bin/bash
bin/console doctrine:fixtures:load
```
I activated the fixtures bundle for all environments to get started quickly. In a real project you should not run any fixtures on your real database as this purges all data from the db.
