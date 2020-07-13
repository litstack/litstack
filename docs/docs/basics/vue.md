# Extend With Vue

The admin interface can be extended with custom Vue components for numerous purposes.

## Setup

To include your own `Vue` components in the admin application, the locale npm package `vendor/aw-studio/laravel-content-administration` has to be installed. This can be done using the following artisan command:

```shell
php artisan fjord:extend
```

At the beginning of your `webpack.mix.js` the import of the fjord mix config will be added automatically. Two files are compiled:

-   `fjord/resources/js/app.js` => `public/fjord/js/app.js`
-   `fjord/resources/sass/app.scss` => `public/fjord/css/app.css`

Add them to **assets** in the config.

```php
// config/fjord.php
'assets' => [
    'js' => '/fjord/js/app.js',
    'css' => [
        '/public/fjord/css/app.css',
        // Add more css files here ...
    ],
],
```

All javascript files can be found in `fjord/resources/js`.

::: tip
Components that are created in the `components` folder are automatically registered.
:::

Run `npm run watch` and you are good to go.

::: warning
Dont forget to compile your assets every time you **update** the package.
:::

## Register Vue Components

Vue Components that are located in the `./fjord/resources/js/components` folder are automatically globally registered. They can be used for example in Pages, Tables or anywhere where you want to include Vue components.

## Bootstrap Vue

To make it easy to build uniform Fjord pages, the package uses [Bootstrap Vue](https://bootstrap-vue.org/docs/components) for all frontend components. Bootstrap Vue comes with a large number of components to cover all the necessary areas needed to build an application.
