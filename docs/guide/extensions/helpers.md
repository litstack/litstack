# Helpers

Fjord includes a variety of global helper PHP functions and Vue mixins.

## PHP helpers

- [fjord_user](#method-php-fjord_user)
- [fjord()->installed](#method-php-fjord-installed)

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



## Vue mixins

- [can](#method-vue-can)

<a name="method-vue-can"></a>
#### `can('permission')`

The `can` mixin checks if the authenticated user has a permission.

```js
<template v-if="can('read message')">
    Message
</template>
```
