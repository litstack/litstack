# Boolean

A boolean field.

```php
$form->boolean('active')
    ->title('Live')
    ->hint('Put the site online.')
    ->width(1/3);
```

Add the `array` [cast](https://laravel.com/docs/5.2/eloquent-mutators#attribute-casting) to your model:

```php
protected $casts = [
    'active' => 'boolean'
];
```

## Methods

| Method          | Description                                                      |
| --------------- | ---------------------------------------------------------------- |
| `title`         | The title description for this field.                            |
| `hint`          | A closure where all repeatable blocks are defined.               |
| `width`         | Width of the field.                                              |
| `rules`         | Rules that should be applied when **updating** and **creating**. |
| `creationRules` | Rules that should be applied when **creating**.                  |
| `updateRules`   | Rules that should be applied when **updating**.                  |
