# Localization

In Fjord can be managed multilingual. The translation in the frontend can be done in `php` using [laravel's localization](https://laravel.com/docs/7.x/localization) service or in `vue` using [vue-i18n](https://kazupon.github.io/vue-i18n/guide/formatting.html). It uses the format of `laravel`, all translation strings are formatted to use them in `vue-i18n`.

## PHP

To compile for the locale of the Fjord interface the `__f()` method is used, just like `__()` from laravel's [localization](https://laravel.com/docs/7.x/localization#retrieving-translation-strings).

```php
__f('fjord.title')
```

## Vue

In `Vue` the [vue-i18n](https://kazupon.github.io/vue-i18n/introduction.html) format is used.

```vue
<template>
    <div>
        {{ $t('fjord.title') }}
    </div>
</template>
```

## Add Path

By default the path `fjord/resources/lang` is imported for the Fjord translation. You can register as many paths with localizations files in your service providers as you want. However it is recommended to separate translations for the Fjord application and your website.

```php
use Fjord\Support\Facades\FjordLang;

FjordLang::addPath(base_path('yourpath/lang/'));
```
