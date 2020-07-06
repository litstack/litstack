# Checkboxes

Multiple checkboxes.

```php
$form->checkboxes('fruits')
    ->title('Fruits')
    ->options([
        'orange' => 'Orange',
        'apple' => 'Apple',
        'pineapple' => 'Pineapple',
        'grape' => 'Grape',
    ])
    ->hint('Select your fruits.')
    ->width(6);
```

Add the `array` [cast](https://laravel.com/docs/5.2/eloquent-mutators#attribute-casting) to your model:

```php
protected $casts = [
    'fruits' => 'array'
];
```

## Methods

| Method          | Description                                                            |
| --------------- | ---------------------------------------------------------------------- |
| `title`         | The title description for this field.                                  |
| `hint`          | A short hint that should describe how to use the field.`               |
| `width`         | Width of the field.                                                    |
| `options`       | An array with checkboxe values and descriptions.                       |
| `stacked`       | Places each checkbox one over the other instead of next to each other. |
| `rules`         | Rules that should be applied when **updating** and **creating**.       |
| `creationRules` | Rules that should be applied when **creating**.                        |
| `updateRules`   | Rules that should be applied when **updating**.                        |
