# Route Field

With the route picker field you can select a route from a predefined list.

## Register Routes

To be able to select routes in your route field you must first register a route collection in a [service provider](https://laravel.com/docs/7.x/providers). The registered collection can then be used again and again in different places. The following example registers a collection with the name **app**.

```php
use Fjord\Crud\Fields\Route;

Route::register('app', function($collection) {
    // Define your route collection in here.
});
```

### Add Route

The selectable routes are configured in the closure using the `route` method. The first parameter is the **title** that represents the route and the second parameter is a function that returns the actual route.

```php
$collection->route('Home', function() {
    return route('home');
});
```

:::tip
Use the [arrow function](https://www.php.net/manual/en/functions.arrow.php) short cut:

```php
$collection->route('Home', fn($locale) => route('home'));
```

:::

### Add Group

You can group routes for example into model show routes.

```php
$collection->group('Articles', function($group) {
    $articles = Article::all();

    foreach($articles as $article) {
        $group->route($article->title, fn() => route('articles.show', $article->id));
    }
});
```

### Localization

The closure in which the route is returned is given the current **locale** as parameter.

```php
$collection->route('Home', function($locale) {
    return route("{$locale}.home");
});
```

## Example

```php
$form->route('route')
    ->collection('app')
    ->title('Picke a Url');
```

## Prepare Model

If the route field is used in Models the route cast must be specified:

```php
use Fjord\Crud\Casts\Route;

$casts = [
    'route' => Route::class
];
```
