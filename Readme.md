# Fjord: A Laravel Admin-Panel / CMS

Fjord is a multilanguage admin-panel/cms scaffolding package that helps you creating CRUD in seconds via Artisan-commands. It also lets you manage the "static" content of each of your websites pages (Headlines, Text, Images), as well repetitive mixed contents you define. Generating content as well as passing it to your views and retrieving it is super simple.

## Installation

Install this package using the following commands:

```bash
composer require aw-studio/fjord
php artisan fjord:install
```

First, migrate the Laravel's default tables running:

```bash
php artisan migrate
```

You can easily create a new admin-user by running:

```bash
php artisan fjord:admin
```

It's all setup now, visit http://yourapp.tld/admin

## Setup

If your application is multilingual edit the `config/translatable.php` config
and set the locals to your needing:

```php
'locales' => [
    'de',
    'en'
],
```

## Extend Fjord Vue Application

To use the npm fjord package you must install the local package that is located in the vendor folder:

```
npm i vendor/aw-studio/fjord
```

You can now extend the Fjord Vue Application:

```javascript
import Fjord from 'fjord';

const store = {};

const mixins = {};

new Fjord({
    store,
    mixins
});
```
