# Table

[[toc]]

`tables` can be easily configured in the backend. You can easily display attributes, relationships or include your own `Vue` components to adjust the table as needed. The following explains how to customize the tables to your needs.

## Text

Casual text columns are added with the function `col($label)`. Attached are all methods for configuring the column.

```php
$table->col('Name');
```

The `value` of the column is indicated by the function value. You can specify model attributes in curly brackets to include them in the text flow.

```php
$table->col('Name')->value('{first_name} {last_name}');
```

It is also possible to specify the attribute of relations. In this case attributes must be separated with a dot like this:

```php
$table->col('Product')->value('{product.name}');
```

Maybe you want to display a value representative for a state, this can be achived by passing the attribute name as the first and an array of options as the second parameter to the value method:

```php
use App\Modols\Product;

$table->col('State')->value('state', [
    Product::AVAILABLE    => 'available',
    Product::OUT_OF_STOCK => 'out of stock',
]);
```

## Text Align

You may set the text align to right like shown in the following examples:

```php
$table->col('amount')->value('{amount} €')->right();
$table->col('state')->value('{state}')->center();
```

## Small

With the function `small` the column is reduced to the minimum width.

```php
$table->col('Icon')
    ->value('{icon}')
    ->small(); // Reduces column to minimum width.
```

## Sortable

A table column can be sorted directly by clicking on the column in the table head. To achieve this, you simply have to specify the name of the attribute you want to sort by.

```php
$table->col('Name')
    ->value('{first_name} {last_name}')
    ->sortBy('first_name'); // Sorting the column by first_name.
```

You may even sort by related column.

```php
$table->col('Product')->value('{product.name}')->sortBy('product.name');
```

:::tip
In case of long loading times when sorted by relation attributes it can help to add an [index](https://laravel.com/docs/7.x/migrations#indexes) on the column that connects the relation.
:::

## Strip Html

For example, if you want to display the value of a `wysiwyg` field, it makes sense to strip the **html tags** and specify a maximum number of characters like this:

```php
$table->col('Text')
    ->value('{text}')
    ->stripHtml()
    ->maxChars(50);
```

## Regex

Perform a regular expression search and replace:

```php
$table->col('Fruit')
    ->value('orange')
    ->regex('/\b(\w*orange\w*)\b/im', 'apple'); // Replaces orange with apple.
```

## Image

If an image is to be displayed in a table, the image url must also be specified using the `src` method. If the image was uploaded via the `Image` Field, the conversions specified in the config file **fjord.php** can be displayed.

```php
$table->image('Image')
    ->src('{image.conversion_urls.sm}');
```

### maxWidth, maxHeight

Furthermore, a `maxWidth` and a `maxHeight` can be defined.

```php
$table->image('Image')
    ->src('{image.conversion_urls.sm}')
    ->maxWidth('50px')
    ->maxHeight('50px');
```

### Square

Set a width and a height if the image should simply be displayed as a square:

```php
$table->image('Image')
    ->src('{image.conversion_urls.sm}')
    ->square('50px'); // Displays the image as 50 x 50 px square using object-fit: cover
```

## Relation

In a normal table column you can directly display attributes for relations. With the relation method, a link to the corresponding CRUD form can be displayed as well. Therefore the `related` name of the relation and `routePrefix` of the corresponding CRUD config must be specified.

```php
use App\Models\Product;

$table->relation('Product')
    ->related('product') // Relation name.
    ->value('{name}') // Related attribute to be displayed.
    ->routePrefix(
        Crud::config(Product::class)->route_prefix
    )
    ->sortBy('product.name');
```

## Toggle

To edit the boolean state of a moel directly in a table, a **switch** can be displayed in a column using `toggle`. The name of the corresponding attribute must be specified as the first parameter. In addition, the `routePrefix` for the update route must be specified, if the table is built in a CRUD or form config, simply use the config function `routePrefix`.

```php
$table->toggle('active')
    ->label('Live')
    ->routePrefix($this->routePrefix())
    ->sortBy('active');
```

## View

With the `view` method you can easily add Blad Views to your table column:

```php
$table->view('columns.hello')->label('Hello');
```

```html
<!-- ./resouces/views/columns/hello.blade.php -->
<div class="badge badge-secondary">
    Hello World!
</div>
```

You can use Vue components in your blade component:

```html
<!-- ./resouces/views/columns/hello.blade.php -->
<b-badge>
    Hello World!
</b-badge>
```

Use the **prop** `item` to display attribute data:

```html
<!-- ./resouces/views/columns/hello.blade.php -->
<b-badge v-html="item.state" />
```

## Component

You can also integrate your own Vue components into columns. The component **name** is specified as the first parameter, the label must be specified separately. Additionally props can be defined.

```php
$table->component('my-table-component')
    ->label('State')
    ->prop('variants' => [
        'active' => 'success',
        'complete' => 'primary',
        'failed' => 'danger'
    ])
    ->sortBy('state');
```

Your component could look like this:

```javascript
<template>
    <b-badge :variant="variants[item.state]">
        {{ item.state }}
    </b-badge>
</template>
<script>
export default {
    name: 'MyTableComponent',
    props: {
        item: {
            required: true,
            type: Object
        },
        vairants: {
            type: Array,
            required: true
        }
    }
}
</script>
```

Read the [Extend Vue](/docs/basics/vue.html#bootstrap-vue) section to learn how to register your own Vue components.
