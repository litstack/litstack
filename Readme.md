# Fjord: Laravel Content-Administration

Fjord is a multilanguage package for building and maintaining the data of your Laravel applications. It provides an intuitive interface and the tools needed to manage your project's content.

It offers Artisan-Commands for generating CRUD-Models and Users. Manage CRUD-Models as well as all Page-Content of your website on a modern yet super simple interface.

![Fjord Interface](https://raw.githubusercontent.com/aw-studio/fjord/master/fjord.png 'Fjord Interface')

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

## CRUD

Create your first CRUD-Model by typing the command:

```bash
php artisan fjord:crud
```

Follow the instructions and your Model will be cruddy in no-time.

![Fjord Interface](https://raw.githubusercontent.com/aw-studio/fjord/master/crud.png 'Fjord CRUD')

## Multilanguage

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

new Fjord({
    store,
});
```
