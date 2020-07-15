# Javascript

[[toc]]

If you build your own Vue components it makes sense to know about the helpers available in Javascript.

## Axios

The javascript global `axios` is configured with the route prefix specified in the config **fjord.php**. It is recommended to set a try around the function when executing an axios request.

```javascript
async func() {
    let response;
    try {
        response = await axios.get('my-route')
    } catch(e) {
        return
    }

    ...
}
```

::: tip
For requests that shouldn't use the fjord `route_prefix` the global `_axios` can be used.
:::

## Helpers

In javascript and all Vue components the global variable `Fjord` is available. This includes the following helpers:

-   `baseURL`
-   `config`

### `Fjord.baseURL`

The `route_prefix` set in config **fjord.php**, with a front slash before and after the prefix.

```php
// config/fjord.php
'route_prefix' => 'admin' // Fjord.baseURL would be /admin/
```

```javascript
<component>
    <a href="`${Fjord.baseURL}my-link`">Link</a>
</component>
```

### `Fjord.config`

All attributes from the config **fjord.php**.

## Event Bus

The event bus can be called via the global Fjord like this:

```javascript
Fjord.bus.$on(event, callback);
Fjord.bus.$once(event, callback);
Fjord.bus.$off(event, callback);
Fjord.bus.$emit(event, callback);
```

## Events

Some useful events:

-   `saved` - Executed when saved.
-   `saveCanceled` - When save has been canceled.
