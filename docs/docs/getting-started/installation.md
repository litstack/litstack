# Installation

## Setup

An existing Laravel project is required to install Fjord. In addition, an existing database connection is required.

## Server Requirements

The server requirements for the Fjord package come with the requirements of `Laravel v7` and some required composer packages.

## Installing Fjord

Installing Fjord into an existing Laravel application via Composer:

```shell
composer require aw-studio/fjord
```

Fjord will automaticly register its service providers. The next step is process all publishes and migrations by typing the following artisan command:

```shell
php artisan fjord:install
```

Now all models have been moved to the `app/models` folder, all needed files have been published and all migrations have been executed.

The admin interface can be reached via the standard route `/admin`. The route may be changed in the config file `fjord.php` by changing the `route_prefix` key.

The Final step is to create an admin user so you can login into the backend:

```shell
php artisan fjord:admin
```

The wizard asks you step by step to type in the required user data.
