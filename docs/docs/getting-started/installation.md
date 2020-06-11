# Installation

[[toc]]

## Setup

An existing Laravel project is required to install Fjord as well as a database connection.

## Requirements

Fjord requires **PHP 7.4+** and **Laravel 7+**. As well as all [requirements](https://docs.spatie.be/laravel-medialibrary/v8/requirements) of **spatie/laravel-medialibrary 8.2+**.

## Installing Fjord

Installing Fjord into an existing Laravel application via Composer:

```shell
composer require aw-studio/fjord
```

Fjord will automatically register the needed service providers. The next step is to process all publishes and migrations by typing the following artisan command:

```shell
php artisan fjord:install
```

Now all models have been moved to the `app/models` folder, all required files have been published and all migrations have been executed.

The admin interface can be reached via the standard route `/admin`. The route may be changed in the config file `fjord.php` by changing the `route_prefix` key.

The final step is to create an admin user so you can log in to the backend:

```shell
php artisan fjord:admin
```

The wizard will guide you through the process of entering the required user data.
