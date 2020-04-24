# Helpers

Fjord includes a variety of global helper PHP functions and Vue mixins.

## PHP helpers

-   [fjord_user](#method-php-fjord_user)
-   [fjord()->installed](#method-php-fjord-installed)
-   [\_\_f](#method-php-f)
-   [fa](#method-php-fa)

<a name="method-php-fjord_user"></a>

#### `fjord_user()`

The `fjord_user` method returns the authenticated Fjord user model.

```php
<span>{{ fjord_user()->email }}</span>
```

<a name="method-php-fjord-installed"></a>

#### `fjord()->installed()`

The `fjord()->installed` method checks if fjord has been installed. This can be usefull in service providers.

```php
public function someMethod() {
    if(! fjord()->installed()) {
        return
    }

    ...
}

```

<a name="method-php-f"></a>

#### `__f($key, $replace)`

The `__f` method returns the translation using the fjord application locale for the authenticated user.

```php
[
    'names' => [
        'singular' => __f('employee'),
        'plural' => __f('employees'),
    ],
]
```

#### `fa($group, $icon)`

The `fa` helper makes it easy to create [Fontawesome](https://fontawesome.com/icons?d=gallery) icons.

```php
fa('home') // <i class="fas fa-home"></i>
fa('fal', 'abacus') // <i class="fal fa-abacus"></i>
```

## Vue mixins

-   [can](#method-vue-can)

<a name="method-vue-can"></a>

#### `can('permission')`

The `can` mixin checks if the authenticated user has a permission.

```js
<template v-if="can('read message')">{{ message }}</template>
```
