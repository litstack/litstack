# Helpers

Fjord includes a variety of global helper PHP functions and Vue mixins.

## PHP helpers

### Fjord Singleton

-   [installed](#method-php-fjord-installed)
-   [route](#method-php-fjord-route)
-   [url](#method-php-fjord-url)

### Miscellaneous

-   [\_\_f](#method-php-f)
-   [fa](#method-php-fa)
-   [fjord](#method-fjord)
-   [fjord_user](#method-php-fjord_user)

### Fjord Singleton

The `Fjord` singleton contains some helpers which are related to the Fjord application.

<a name="method-php-fjord-installed"></a>

#### `installed()`

The `installed` method checks if fjord has been installed. This can be usefull in service providers.

```php
use Fjord\Support\Facades\Fjord;

if(! Fjord::installed()) {
    return;
}
```

<a name="method-php-fjord-route"></a>

#### `route($name)`

If a route is added to the route file `fjord/routes/fjord.php`, the prefix `fjord` is added to the name. The helper route also adds this prefix as well. Depending on your preferences you can use the laravel helper `route` or the Fjord helper to call up a route.

```php
// fjord/routes/fjord.php

Route::get('dashboard', DashboardController::class)->name('dashboard');
```

```php
<a href="{{ Fjord::route('dashboard') }}">Dashboard</a>
// Is the same as:
<a href="{{ route('fjord.dashboard') }}">Dashboard</a>
```

<a name="method-php-fjord-url"></a>

### `url($url)`

If you don't want to use the route name to call a route but directly specify the url, you can use the `url` helper which prepends the `route_prefix` from the config **fjord.php** to your url.

```php
<a href="{{ Fjord::url('dashboard') }}">Dashboard</a>
```

### Miscellaneous

<a name="method-php-f"></a>

#### `__f($key, $replace)`

The `__f` method returns the translation using the Fjord application locale for the authenticated fjord-user.

```php
[
    'names' => [
        'singular' => __f('employee'),
        'plural' => __f('employees'),
    ],
]
```

<a name="method-php-fa"></a>

#### `fa($group, $icon)`

The `fa` helper makes it easy to create [Fontawesome](https://fontawesome.com/icons?d=gallery) icons.

```php
fa('home') // <i class="fas fa-home"></i>
fa('fal', 'abacus') // <i class="fal fa-abacus"></i>
```

<a name="method-php-fjord"></a>

#### `fjord()`

The `fjord` method returns the `Fjord` singelton.

```php
fjord()->installed();

// Is the same as:

use Fjord\Support\Singletons\Fjord;
Fjord::installed();
```

<a name="method-php-fjord_user"></a>

#### `fjord_user()`

The `fjord_user` method returns the authenticated Fjord user model.

```php
<span>{{ fjord_user()->email }}</span>
```

## Vue mixins

-   [can](#method-vue-can)

<a name="method-vue-can"></a>

#### `can('permission')`

The `can` mixin checks if the authenticated user has a permission.

```js
<template v-if="can('read message')">{{ message }}</template>
```
