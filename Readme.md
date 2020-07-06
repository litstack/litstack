<p align="center" style="margin: 50px 0 !important;"><a href="https://www.fjord-admin.com" target="_blank" rel="noopener"><img src="./docs/fjord-logo-padding.svg" width="420"></p>

<p align="center">
    <a href="https://github.com/aw-studio/fjord/actions"><img src="https://github.com/aw-studio/fjord/workflows/tests/badge.svg" alt="Build Status"></a>
    <a href="https://packagist.org/packages/aw-studio/fjord"><img src="https://img.shields.io/packagist/dt/aw-studio/fjord?color=%234951f2" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/aw-studio/fjord"><img src="https://img.shields.io/github/v/release/aw-studio/fjord?color=%2383c2ff&label=stable" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/aw-studio/fjord"><img src="https://img.shields.io/github/license/aw-studio/fjord?color=%2331c653" alt="License"></a>
</p>

# Fjord: Laravel Content-Administration

Fjord is a Laravel package for building Admin-Interfaces that help maintaining the data of your applications. It provides an intuitive interface and the tools needed to manage your project's Users, Models and free Forms for Pages, Settings etc.

![Fjord Interface](./docs/fjord_screen.png 'Fjord Interface')

## Features

-   Code Driven Configuration
-   Using Laravel Standards
-   Extendable via Vue Components
-   Form Fields for Models
-   User Management
-   Role And Permission Management
-   Media Management
-   Translatable
-   Headless

## Learning Fjord

Read the [Docs](https://www.fjord-admin.com) and learn how to build your custom Admin panel. Use your knowledge about Laravel standards and do much work in little time.

Feel free to ask us anything on our [discord chanel](https://discord.gg/u4qpb5P).

## Installation

Install this package using the following commands:

```bash
composer require aw-studio/fjord
php artisan fjord:install
```

Thats it for the installation. You can easily create a new admin-user by running:

```bash
php artisan fjord:admin
```

It's all setup now, visit http://yourapp.tld/admin

## Demo

Try the [Demo](https://demo.fjord-admin.com)

## Testing

Tests are divided into two parts. `PHP` tests for the backend via [PHPUnit](https://phpunit.readthedocs.io/en/9.2/) and `Javascript` test for the frontend via [Jest](https://jestjs.io/). Depending on what you are working on you may only want to test one part.

Installing the test dependencies can be done by installing the composer and/or npm packages:

```shell
composer install && npm install
```

### PHP Tests

Run the php tests via **composer**:

```shell
composer test
```

Some tests require a chromedriver to be running on port `9515`. If you want to cover them as well before pushing to your repository you may start a chromedriver before:

```shell
chromedriver --port=9515
```

### Javascript Tests

Run the javascript tests via **npm**:

```shell
npm test:js
```

## License

Fjord is an open-sourced software licensed under the [MIT license](LICENSE.md).
