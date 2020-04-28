# Localization

In Fjord can be managed multilingual. The translation in the frontend can be done in `php` using [laravel's localization](https://laravel.com/docs/7.x/localization) service or in `vue` using [vue-i18n](https://kazupon.github.io/vue-i18n/guide/formatting.html). It uses the format of `laravel`, all translation strings are formatted to use them in `vue-i18n`.

In Laravel applications that include Fjord, there are **two** different locales, one for your website and one for the Fjord application. So a user can manage German content in the Fjord application and still see the interface in an English version.

The following examples refer to the translations which look like this:

```php
// fjord/resources/lang/{locale}/messages.php

return [
    "welcome": "Welcome, :name"
];
```

## PHP

To compile for the locale of the Fjord interface the `__f()` helper method is used, just like `__()` from laravel's [localization](https://laravel.com/docs/7.x/localization#retrieving-translation-strings).

```php
__f('messages.welcome', ['name', 'Jannes'])
```

Pluralization can be used with the `__f_choice` function or the short version `__f_c`.

```php
'apples' => '{0} There are none|[1,19] There are some|[20,*] There are many',

...

__f_choice('apples', 10)
__f_c('apples', 10)
```

## Vue

In `Vue` the [vue-i18n](https://kazupon.github.io/vue-i18n/introduction.html) format is used.

```vue
<template>
    <div>
        {{ $t('messages.welcome', { name: 'Jannes' }) }}
    </div>
</template>
```

## Determine Locale

To determine the locale the function `getLocale` can be used on the `FjordApp` facade like so:

```php
$fjordLocale = FjordApp::getLocale();

if (FjordApp::isLocale('en')) {
    //
}
```

## Add Path

By default the path `fjord/resources/lang` is imported for the Fjord translation. You can register as many paths with localizations files in your service providers as you want. However it is recommended to separate translations for the Fjord application and your website.

```php
use Fjord\Support\Facades\FjordLang;

FjordLang::addPath(base_path('yourpath/lang/'));
```
