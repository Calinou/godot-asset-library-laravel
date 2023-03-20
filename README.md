___


> **Warning**
> 
> This repository is discontinued and will no longer be worked on.
> See [issue #467](https://github.com/Calinou/godot-asset-library-laravel/issues/467) for an explanation.

___

# Godot Asset Library

[![codecov](https://codecov.io/gh/Calinou/godot-asset-library-laravel/branch/master/graph/badge.svg)](https://codecov.io/gh/Calinou/godot-asset-library-laravel)

![Screenshot](https://raw.githubusercontent.com/Calinou/media/master/godot-asset-library-laravel/screenshot.png)

## Installation

If you wish to contribute code to the Godot Asset Library, you'll need to
install a local copy so you can test your changes.

To run it locally, you need to install and build both the backend and frontend.

### Browser support

When working on new features, keep in mind this website only supports
*evergreen browsers*:

- Chrome (latest version and N-1 version)
- Edge (latest version and N-1 version)
- Firefox (latest version, N-1 version, and latest ESR version)
- Opera (latest version and N-1 version)
- Safari (latest version and N-1 version)

Internet Explorer isn't supported.

### Development environment

This project uses [Laravel Sail](https://laravel.com/docs/sail) to supply you with a Docker-based
development environment. Please take a moment to familiarize yourself with its concepts.

We also supply a small convenience shell script named `sail` in the project root which forwards all
commands to the `vendor/bin/sail` command.

It also asks whether it should
- [install the composer dependencies using a container](https://laravel.com/docs/sail#installing-composer-dependencies-for-existing-projects),
  if not finding the sail command.
- install `.env.sail` as the current `.env`, if none is currently present.  
  **NOTE:** If you already have an `.env` installed, consider replacing it with `.env.sail`
  as the `DB_*`/`REDIS_*` settings are important for operating inside the sail environment.

```bash
# Start the development environment
./sail up -d

# Continue with setting up the backend...
./sail artisan db:create
./sail artisan migrate --seed
./sail artisan key:generate
./sail artisan admin:create

# ...and the frontend
./sail yarn
./sail yarn development
```

The development environment will be available at http://localhost:8080 by default.

```bash
# Stop the development environment
./sail stop
```

### Production environment

The production environment uses **PHP 7.3**. To preserve compatibility with the
production environment, don't use language features only available in PHP 7.4 or
later.

### Backend

The backend uses the [Laravel](https://laravel.com/) PHP framework.

1. Install dependencies as described on the
   [Laravel 8.x installation page](https://laravel.com/docs/8.x/installation).
2. Set up a [MySQL](https://www.mysql.com)
   or [MariaDB](https://mariadb.org/) database.
3. Copy the `.env.example` file as `.env` and edit the `DB_*` variables
   to add database credentials.
4. Run the following commands in order:

```bash
# Install composer dependencies
composer install

# Create a database (credentials must be set in `.env` first)
php artisan db:create

# Run migrations and seed test data into the database
php artisan migrate --seed

# Create application key
php artisan key:generate

# Create an user with administrator privileges
# (you will be prompted for username/email/password)
php artisan admin:create

# Run a local Web server for development
php artisan serve
```

You can run `php artisan migrate:refresh --seed` to run all migrations
and seed test data again.

#### Code quality tools

- Feature tests are available. They use a secondary MySQL database configured
  in `.env.testing`. You need to create this database before running them.
- This project follows the Laravel code style,
  applied using [PHP CS Fixer](https://github.com/FriendsOfPhp/PHP-CS-Fixer).
- Code is analysed with PHPStan thanks to
  [Larastan](https://github.com/nunomaduro/larastan).

Use the commands below:

```bash
# Run unit and feature tests
vendor/bin/phpunit

# Check PHP code for possible errors
vendor/bin/phpstan analyse

# Try to fix code style violations automatically
vendor/bin/php-cs-fixer fix
```

### Frontend

The frontend uses the [Tailwind](https://tailwindcss.com/) CSS framework
and [TypeScript](https://www.typescriptlang.org/).

1. Install [Node.js](https://nodejs.org/en/) (10.x or later)
   and [Yarn](https://nodejs.org/en/) (recommended over npm).
2. Run the following commands in the project folder depending on your needs:

```bash
# Install dependencies (must be done before other Yarn commands)
yarn

# Build frontend files and watch for changes
yarn watch

# Build optimized frontend files for production
yarn production

# Lint CSS and TypeScript files for code style violations
yarn lint

# Try to fix code style violations automatically
yarn lint:css --fix
yarn lint:ts --fix
```

## License

Copyright © 2019-2021 Hugo Locurcio and contributors

Unless otherwise specified, files in this repository are licensed under
the MIT license; see [LICENSE.md](LICENSE.md) for more information.
