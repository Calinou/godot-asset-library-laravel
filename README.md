# Godot Asset Library

[![codecov](https://codecov.io/gh/Calinou/godot-asset-library-laravel/branch/master/graph/badge.svg)](https://codecov.io/gh/Calinou/godot-asset-library-laravel)

## Installation

If you wish to contribute code to the Godot Asset Library, you'll need to
install a local copy so you can test your changes.

To run it locally, you need to install and build both the backend and frontend.

### Backend

The backend uses the [Laravel](https://laravel.com/) PHP framework.

1. Install dependencies as described on the
   [Laravel 6.x installation page](https://laravel.com/docs/6.x/installation).
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

-   Feature tests are available. They use a secondary MySQL database configured
    in `.env.testing`. You need to create this database before running them.
-   This project follows the Laravel code style,
    applied using [PHP CS Fixer](https://github.com/FriendsOfPhp/PHP-CS-Fixer).
-   Code is analysed with PHPStan thanks to
    [Larastan](https://github.com/nunomaduro/larastan).

Use the commands below:

```bash
# Run unit and feature tests
composer test

# Check PHP code for possible errors
composer analyse

# Try to fix code style violations automatically
composer format

# Helps to find duplicated code
composer cpd

# Detect mess in the code (it generates an report that can be found at './phpmd.html')
composer md
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
yarn prod

# Lint CSS and TypeScript files for code style violations
yarn lint

# Try to fix code style violations automatically
yarn lint:css --fix
yarn lint:ts --fix
```

## License

Copyright © 2019-2020 Hugo Locurcio and contributors

Unless otherwise specified, files in this repository are licensed under
the MIT license; see [LICENSE.md](LICENSE.md) for more information.
