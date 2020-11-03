# Blog using Symfony 5, Twig and Materialize

## Install dependencies

```bash
  composer install
```

## Launch the php server and the mysql and phpmyadmin containers

```bash
  symfony serve -d --no-tls
  docker-compose up -d
```

**Note: -d for mode detach, -d --no-tls for disable TLS encryption**

Check the project at url: http://127.0.0.1:8000

## Configure and setup database

Create file ".env.local" and inform the DATABASE_URL (check the docker-compose file)

**Example:** DATABASE_URL=mysql://root:**db_password**@127.0.0.1:6603/**db_name**

Create the database and lauch the migrations

```bash
  php bin/console doctrine:database:create
  php bin/console doctrine:migrations:migrate
```

## Load the fake data 

```bash
  php bin/console doctrine:fixtures:load
```

## Additional features

**Note: Turial based on https://www.kaherecode.com/tutorial/creer-un-blog-avec-symfony4-partie-1

* Finalization of crud operations
* Adding comments and a profile page
* Creation of a service for file upload
* Update of various templates
* Fixtures for users, categories and articles
