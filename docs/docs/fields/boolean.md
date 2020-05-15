# Boolean

A boolean field.

```php
$form->boolean('active')
    ->title('Live')
    ->hint('Put the site online.')
    ->cols(3);
```

Add the `array` [cast](https://laravel.com/docs/5.2/eloquent-mutators#attribute-casting) to your model:

```php
protected $casts = [
    'active' => 'boolean'
];
```

## Methods

| Method  | Description                                        |
| ------- | -------------------------------------------------- |
| `title` | The title description for this field.              |
| `hint`  | A closure where all repeatable blocks are defined. |
| `cols`  | Cols of the field.                                 |
