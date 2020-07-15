# Actions

Actions are a useful feature to create reusable tasks for models. These can then be inserted at the desired position, in your index table or on a detail page of a model.

## Create

Actions can be created via artisan.

```shell
php artisan fjord:action MyAction
```

These then end up in the folder `./fjord/app/Actions`.

```php
<?php

namespace FjordApp\Actions;

use Illuminate\Support\Collection;

class MyAction
{
    public function run(Collection $models)
    {
        // Do Something.
    }
}
```

## Display

Actions can be added to a table using the action method. To do this, the title and namespace of the action must be specified as parameters.

```php
// CrudConfig

use FjordApp\Actions\MyAction;

public function index(CrudIndex $page)
{
    $page->table(...)
        ->action('My Action', MyAction::class);
}
```

Actions can also be displayed on the show page of a crud, even in the slots `navigationLeft`, `navigationRight`, `navigationControls`, `headerLeft` and `headerRight`:

![navigation](./../basics/screens/page_slots.jpg 'navigation')

```php
// CrudConfig

use FjordApp\Actions\MyAction;

public function show(CrudShow $page)
{
    $page->navigationLeft()->action('My Action', MyAction::class);
    $page->navigationRight()->action('My Action', MyAction::class);
    $page->navigationControls()->action('My Action', MyAction::class);
    $page->headerLeft()->action('My Action', MyAction::class);
    $page->headerRight()->action('My Action', MyAction::class);
}
```

The `variant` of the actions that are displayed as buttons can be adjusted:

```php
$page->headerLeft()->action('My Action', MyAction::class)->variant('outline-primary');
```
