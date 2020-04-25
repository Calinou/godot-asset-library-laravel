
# Using with docker

This setup comes with customized php:7.2 (preset for laravel; extended with node and yarn; based on [this image](https://hub.docker.com/r/webdevops/php-apache-dev)), [mysql:5.7](https://hub.docker.com/_/mysql) and [phpmyadmin](https://hub.docker.com/r/phpmyadmin/phpmyadmin).

__Requirements__: bash, docker, make

## Setup

### 1. Prepairing environment:

From project root execute:

```sh
cp .env.example .env
```

Open `.env`, set `DB_HOST` to `mysql` and set `DB_PASSWORD`

### 2. Prepairing docker-compose:

```sh
cp docker/docker-compose.yml.example docker/docker-compose.yml
```

If you prefer to use local database you can disable mysql and phpmyadmin by removing them from `docker/docker-compose.yml` and setting proper db connection settings in `.env`.

### 3. Setting XDebug defauts (optional):

```sh
cp docker/dev.php.ini.example docker/dev.php.ini
```

More about XDebug setup in [here](#xdebug)

### 4. Building and running container:

```sh
# Suggested usage (required for XDebug)
make docker-build docker
make docker-shell

# Or if you not using XDebug you can use plain old docker-compose
docker-compose -f docker/docker-compose.yml [up|down|build|...]
```

### 5. Finishing steps:

Go to [Backend installation](https://github.com/Calinou/godot-asset-library-laravel#backend) and complete it (no need to run `php artisan serve`).
If `php artisan db:create` fails at first, wait a while, database setup might take some time.

## Usage:

After setup is done you can run containers by using `make docker`, and stop them by `make docker-down` (more on make targets [here]((#make-usage))).

If you prefer you can use `docker-compose -f docker/docker-compose.yml [up|down|build|...]`

## XDebug:

[Example setup](https://github.com/webdevops/php-docker-boilerplate/blob/master/documentation/DOCKER-INFO.md#xdebug-remote-debugger-phpstorm)

__Caution__: By default this project is setup to use XDebug on port `9001` because port `9000` is already taken by PHP-FPM. You can tweak xdebug settings in `docker/dev.php.ini` (`make docker-build` is required after changing settings).

## Make Usage:

More on existing make commands [here](Makefile.docker)
