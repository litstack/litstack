# Extend With Vue

The Fjord interface can be extended with custom Vue components for numerous purposes.

## Setup

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

All javascript files are found in `fjord/resources/js`.

::: tip
Components that are created in the `components` folder are automaticly registered
:::

Run `npm run watch` and you are good to go.

## Bootstrap Vue

To make it easy to build uniform Fjord pages, Fjord uses [Bootstrap Vue](https://bootstrap-vue.org/docs/components) for all frontend components. Bootstrap Vue comes with a large number of components to cover all the necessary areas needed to build a site.

## Build Your Own Page

To build a new page for your Fjord application you have to view your root component. This is done by giving the View `fjord::app` the name of your `Vue` component and passing the required data as props like so.

```php
use App\Models\Post;

return view('fjord::app')
    ->withComponent('my-component') // Name of your Vue component.
    ->withTitle('My Component') // Html title.
    ->props([
        'posts' => Post::all()
    ]);
```

In this case `posts` is passed as prop to the component `my-component`.

The following example shows how to build a root component of a page for a Fjord application.

```javascript
<template>
    <fj-container>
        <fj-navigation/>
        <fj-header title="Posts"/>

        <b-row>
            <b-col cols="3" v-for="(post, key) in posts" :key="key">
                <b-card :title="post.title">
                    {{ post.text }}
                </b-card>
            </b-col>
        </b-row>
    </fj-container>
</template>
<script>
export default {
    name: 'MyComponent',
    props: {
        posts: {
            required: true,
            type: Array
        }
    }
}
</script>
```

::: tip
Read more about how to build your custom page in the [Vue Components](/docs/frontend/components.html#custom-pages) section.
:::
