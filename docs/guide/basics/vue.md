# Extend Vue

To include your own `Vue` components in the Fjord application, the locale npm package `vendor/aw-studio/fjord` must be installed. This can be done using the artisan command `fjord:extend`:

```shell
php artisan fjord:extend
```

All Fjord mixins are required in your `webpack.mix.js`.

In order to load the custom `app.js` you have to include it in your `config/fjord.php`

```php
'assets' => [
    // Set path to the app.js file in your public folder
    'js' => '/fjord/app.js',
    'css' => [
        // Put path to css files that should be included here...
    ],
],
```

Run `npm run watch` and you are good to go.
