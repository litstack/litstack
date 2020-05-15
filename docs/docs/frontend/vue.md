# Vue

[[toc]]

## Mixins

-   [can](#mixin-can)
-   [user](#mixin-user)

<a name="mixin-can"></a>

### `can({permission})`

The `can` mixin checks if the authenticated user has a permission.

```js
<template v-if="can('read message')">{{ message }}</template>
```

<a name="mixin-user"></a>

### `user()`

The `user` mixin returns the authenticated FjordUser Model.

```js
<span>{{ user().name }}</span>
```

## Localization

[i18n-vue](https://kazupon.github.io/vue-i18n/docs/formatting.html) is used for the translation in Vue. All translations that are available in **php** are available in **i18n-vue** as well like shown in the example:

```php
// fjord/resources/lang/{locale}/message.php
return [
    "welcome": "Welcome to Fjord"
];
```

```javascript
<div>
    {{ $t('message.welcome') }}
</div>
```
