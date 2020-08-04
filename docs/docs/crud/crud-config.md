# CRUD-Config

The CRUD config returns an array in which the editability and further points are determined.

-   `preview_route` Route to display the model (optional)
-   `index` Index table configuration
-   `names` custom singular and plural names for the model (optional)
-   `controller` name of custom controller (optional)
-   `form_fields` Form Field groups

## Preview Route

The preview route is optional and can be set to view the edited model directly in `mobile`, `tablet` and `desktop` view. The default device can be specified in the fjord config under the key `crud.preview.default_device`.

There are several ways to define a route that can be used according to your needs.

-   As a string:

```php
<?php

return [
    'preview_route' => route('article'),
    ...
];
```

-   As a method:

```php
<?php

return [
    'preview_route' => function($article) {
        return route('article', ['slug' => $article->slug]);
    },
    ...
];
```

-   As a class method:

```php
<?php

use App\Models\Article;

return [
 'preview_route' => [Article::class, 'getRoute'],
 ...
];

```

Inside the class:

```php
<?php

namespace App\Models;

use Fjord\Fjord\Models\Model as FjordModel;

class Article extends FjordModel
{
    ...
    public function getRoute()
    {
        return route('artikel', [
            'slug' => $this->slug
        ]);
    }
    ...
}
```

## Names

You may want to show a different model name than the actual one to your editors.
Specify a singular and a plural name like so:

```php
<?php

return [
    ...
    'names' => [
        'singular' => 'Beitrag',
        'plural' => 'Beiträge'
    ],
    ...
];
```

## Controller

In some cases it will be necessary to set a custom controller.
Define it by setting a controller:

```php
<?php

return [
    ...
    'controller' => 'App\Http\Controllers\Fjord\MyArticleController',
    ...
];
```

## Index

The index table for the CRUD model can be reached at `/{table_name}`.

The attributes that should be searched for are determined in `search`:

```php
...
'index' => [
  'search' => ['title', 'text', 'slug'],
]
```

The sorting possibilities are defined in `sort_by` by setting the attribute and the sorting direction as the key and the description as the value like `{key}.{asc|desc} =>{description}`. You can specify `sort_by_default` if you don't want the system to search for the first key automatically.

```php
...
'index' => [
  'id.asc' => 'Id Ascending',
  'id.desc' => 'Id Descending',
  'active.desc' => 'Live First',
  'active.asc' => 'Offline First',
]
```

## Form Fields

For each fillable attribute of the model form fields can be specified to make them editable. [Read more about form fields](/{{route}}/{{version}}/form-fields).

Form fields used for a CRUD model may in some cases come with requirements described below.

-   The `boolean` field needs the boolean [cast](https://laravel.com/docs/6.0/eloquent-mutators#attribute-casting) for the given attribute:

```php
<?php

namespace App\Models;

use Fjord\Fjord\Models\Model as FjordModel;

class Article extends FjordModel
{

    ...

    protected $casts = [
        'active' => 'boolean'
    ];

    ...

}
```

-   The `image` field attribute must be set in the model:

```php
<?php

namespace App\Models;

use Fjord\Fjord\Models\Model as FjordModel;

class Article extends FjordModel
{
    protected $appends = ['image'];

    ...

    public function getImageAttribute()
    {
        return $this->getMedia('image');
    }

    ...
}
```
