# Table

Fjord `tables` can be easily configured in the backend and if desired be expanded in Vue. For example, there are tables for the `CRUD` index or the preview of a relation **Field**. The following explains how to customize the tables to your needs.

## Text

Casual text columns are added with the function `col({label})`. Attached are all methods for configuring the column.

```php
$table->col('Name');
```

The `value` of the column is indicated by the function value. You can specify model attributes in curly brackets to include them in the text flow.

```php
$table->col('Name')->value('{first_name} {last_name}');
```

It is also possible to specify the attribute of relations, for this attributes must be separated with a dot like so.

```php
$table->col('Product')->value('{product.name}');
```

## Small

With the function `small` the column is reduced to the minimum width.

```php
$table->col('Icon')
    ->value('{icon}')
    ->small(); // Reduces column to minimum width.
```

## Sortable

A table column can be sorted directly by clicking on the column in the table head. To do so, you just have to specify the name of the attribute you want to sort by.

```php
$table->col('Name')
    ->value('{first_name} {last_name}')
    ->sortBy('first_name'); // Sorting the column by first_name.
```

## Strip Html

For example, if you want to display the value of a `wysiwyg` Field, it makes sense to strip the **html tags** and specify a maximum number of characters like so:

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

Furthermore, a `maxWidth` and a `maxHeight` can be defined.

```php
$table->image('Image')
    ->src('{image.conversion_urls.sm}')
    ->maxWidth('50px')
    ->maxHeight('50px');
```

order if the image should simply be displayed as a square:

```php
$table->image('Image')
    ->src('{image.conversion_urls.sm}')
    ->square('50px'); // Displays the image as 50 x 50 px square using object-fit: cover
```

## Relation

If relations are displayed, a link to the corresponding `CRUD` config can be displayed directly like so:

```php
use App\Models\Product;

$table->relation('Product')
    ->related('product') // Relation name.
    ->value('{name}') // Related attribute.
    ->routePrefix(
        // The route_prefix of the CRUD config must be specified.
        Crud::config(Product::class)->route_prefix
    )
    ->sortBy('product.name');
```

## Component

You can also integrate your own Vue components. The component **name** is specified as parameter, the label must be specified separately. Additionally props can be defined.

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
