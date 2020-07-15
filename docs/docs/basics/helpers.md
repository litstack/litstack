# Helpers

[[toc]]

The package includes a variety of global helper **PHP** `functions`.

::: tip
The package includes useful **Vue** `mixins` as well. Read the [Vue mixins](/docs/frontend/vue#mixins) section to learn more about the existing `mixins`.
:::

### Fjord Facade

-   [installed](#method-php-fjord-installed)
-   [route](#method-php-fjord-route)
-   [url](#method-php-fjord-url)

### Miscellaneous

-   [\_\_f](#method-php-f)
-   [fa](#method-php-fa)
-   [fjord](#method-fjord)
-   [fjord_user](#method-php-fjord_user)

## Fjord Facade

The `Fjord` singleton contains some helpers which are related to the application.

<a name="method-php-fjord-installed"></a>

### `installed()`

The `installed` method checks if the package has been installed. This can be useful in service providers.

```php
use Fjord\Support\Facades\Fjord;

if(! Fjord::installed()) {
    return;
}
```

<a name="method-php-fjord-route"></a>

### `route($name)`

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

## Miscellaneous

<a name="method-php-f"></a>

### `__f($key, $replace)`

The `__f` method returns the translation using the application locale for the authenticated user.

```php
__f('messages.welcome', ['name' => 'Spatie'])
```

<a name="method-php-fa"></a>

### `fa($group, $icon)`

The `fa` helper makes it easy to include [Fontawesome](https://fontawesome.com/icons?d=gallery) icons.

```php
fa('home') // <i class="fas fa-home"></i>
fa('fal', 'abacus') // <i class="fal fa-abacus"></i>
```

<a name="method-php-fjord"></a>

### `fjord()`

The `fjord` method returns the `Fjord` singelton.

```php
fjord()->installed();

// Is the same as:

use Fjord\Support\Singletons\Fjord;
Fjord::installed();
```

<a name="method-php-fjord_user"></a>

### `fjord_user()`

The `fjord_user` method returns the authenticated user model.

```php
<span>{{ fjord_user()->email }}</span>
```
