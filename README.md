# Back End Test Project ELSE

## Libraries used

### Symfony

symfony was one of the first frameworks created for php, send used as the basis for almost every framework created after.

## how to build

add database address in .env file

```bash
  DATABASE_URL="postgresql://postgres:postgres@postgres-symfony-test:5432/php-sy-test?serverVersion=11&charset=utf8"
```

To terminal run the command below

```bash
  docker-compose up -d
```

With the containers running, access the php container and install the composer dependencies

```bash
  docker exec -it php-symfony-test bash
```

```bash
  composer install
```

create the tables in the database through migration

```bash
php bin/console doctrine:migrations:migrate
```

## Api documentation

```bash
http://localhost/documentation
```
