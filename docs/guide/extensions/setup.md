# Setup

Writing custom components in Fjord can easily be achieved by installing the npm package via

```
npm i vendor/aw-studio/fjord
```

and then extending the application by setting up an `app.js` file in your resources directory as follows:

```javascript
import Fjord from 'fjord';

// register your compnets here

const store = {};

new Fjord({
    store
});
```

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

and render it each time you make changes, for exampe via larvel mix using `npm run dev`:

```js
mix.webpackConfig({
    resolve: {
        alias: {
            '@fj-js': path.resolve(
                __dirname,
                'vendor/aw-studio/fjord/resources/js/'
            ),
            '@fj-sass': path.resolve(
                __dirname,
                'vendor/aw-studio/fjord/resources/sass/'
            )
        }
    }
});

mix.js('resources/js/fjord/app.js', 'public/fjord/js');
```