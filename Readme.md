# Fjord: A Laravel Admin-Panel / CMS

Fjord is a multilanguage CMS/Admin-Panel.

It offers Artisan-Commands for generating CRUD-Modelst and Users. Manage CRUD-Models as well as all Page-Content of your website on a modern yet super simple Interface.

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

new Fjord({
    store
});
```
