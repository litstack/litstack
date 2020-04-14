# Localization

In Fjord can be managed multilingual. The translation in the frontend can be done in `php` using [laravel's localization](https://laravel.com/docs/7.x/localization) service or in `vue` using [vue-i18n](https://kazupon.github.io/vue-i18n/guide/formatting.html). It uses the format of `laravel`, all translation strings are formatted to use them in `vue-i18n`.

## PHP

To compile for the locale of the fjord interface the `__f()` method is used, just like `__()` from laravel.

```php
__f('fjord.title')
```

## Vue

In Vue wird das `vue-i18n` format genutzt.

```vue
<template>
    <div>
        {{ $t('fjord.title') }}
    </div>
</template>
```

## Add Path

By default the path `resources/lang` is imported for the Fjord translation. You can add as many paths with localizations files as you want like in the example.

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class YourServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        fjord()->addLangPath(resource_path('lang/'));
    }

    ...
}
```
