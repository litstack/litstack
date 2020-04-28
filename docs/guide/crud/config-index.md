# Index Config

In the Config for a CRUD model its index table is defined. You can easily display attributes or relations or include your own `Vue` component and customize the table as you need it.

## Query

In the `indexQuery` function the `query` for the index table is initialized. Here you have to specify all the `relations` that should be searchable or to be displayed in the table.

```php
use Illuminate\Database\Eloquent\Builder;

public function indexQuery(Builder $query)
{
    $query->with('department')
        ->withCount('projects_count');

    return $query;
}
```

## Search

For the search, all model `attributes` to be searched for are specified. You can also specify `relations` and their `attributes`.

```php
public $search = ['title', 'department.name'];
```

## Sort

You can search for all model `attributes` as well as `relations` `attributes`. The search attributes are specified as follows: `{attributes}.{desc|asc}`. The default attribute to search for is specified in the `$sortByDefault` property.

```php
public $sortByDefault = 'id.desc';
```

In this example you can see how the array for the search attributes can look like to sort by `id` or by a `relation`.

```php
public function sortBy()
{
    return [
        'id.desc' => 'New first',
        'id.asc' => 'Old first',
        'department.name.desc' => 'Department A-Z',
        'department.name.asc' => 'Department Z-A'
    ];
}
```

## Filter

Filters are specified in groups. Laravel's model [`scopes`](https://laravel.com/docs/7.x/eloquent#local-scopes) are used to filter the index table as shown in the example:

```php
// Config
public function filter()
{
    return [
        'Department' => [
            'development' => 'Development',
            'marketing' => 'Marketing',
        ],
    ];
}
```

```php
// Model
public function scopeDevelopment()
{
    return $this->hasOne('App\Models\Department')->where('name', 'development');
}

public function scopeMarketing()
{
    return $this->hasOne('App\Models\Department')->where('name', 'marketing');
}
```

## Columns

All columns are defined in the `index` function.

```php
use Fjord\Vue\Crud\CrudTable;

public function index(CrudTable $table)
{
    // Build your index in here.
}
```

### `col`

Normal columns are added with the function `col({title})`. Attached are all methods for configuring the column.

```php
$table->col('Name')->value('name')->sortBy('name');
```

All available methods are:
| Method | Description |
| -------- | ------------------- |
| `value` | Table column value. |
| `small` | Reduces the column to the minimum width. |
| `sortBy` | Sort key. |

In the Value you can use the `i18n` syntax to enclose attributes in text or append several attributes together like so:

```php
$table->col('Name')
    ->value('{first_name} {last_name}')
    ->sortBy('last_name');
```

### `component`

With the component method you can include your own `Vue` components.

```php
$table->component('my-component')->label('Name');
```

```javascript
<template>
    <span class="badge badge-primary">{{ item.name }}</span>
</template>
<script>
export default {
    name: 'MyComponent',
    props: ['item']
};
</script>
```

All available methods for the component are:
| Method | Description |
| -------- | ------------------- |
| `label()` | Column label. |
| `prop({name}, {value})` | Add component prop. |
| `props({array})` | Add multiple component props. |
| `sortBy` | Sort key. |

### Existing Component

Fjord brings some useful components that can be used in tables.

-   `fj-col-image`
-   `fj-col-crud-relation`

#### `fj-col-image`

Displays an image. The `src` attribute specifies the url to the image. The urls for the conversions set in the config **fjord.php** can be retrieved from the image using the `conversion_urls` attribute. If your image would be the `image` attribute in your Model, `src` could be `image.conversion_urls.sm`.

Furthermore a `maxWidth` and a `minWidth` can be specified.

```php
$table->component('fj-col-image')
    ->src('{image.conversion_urls.sm}')
    ->maxWidth('50px')
    ->label('Image')
    ->small();
```

#### `fj-col-crud-relation`

Shows a relation and a button with the link to the crud page.

```php
use Fjord\Support\Facades\Fjord;

$table->component('fj-col-crud-relation')
    ->props([
        'related' => 'department',
        'value' => 'name',
        'route_prefix' => Fjord::config('crud.department')->route_prefix
    ])
    ->label('Department')
    ->sortBy('department.name');
```
