# Range

A range slider.

![Range slider](./screens/range/example.png 'Range slide')

```php
 $form->range('range')
    ->title('Range')
    ->min(1)
    ->max(4)
    ->step(1)
    ->hint('Range.')
    ->width(1/2);
```

## Methods

| Method          | Description                                                      |
| --------------- | ---------------------------------------------------------------- |
| `title`         | The title description for this field.                            |
| `hint`          | A short hint that should describe how to use the field.`         |
| `width`         | Width of the field.                                              |
| `min`           | Minimum value.                                                   |
| `max`           | Maximum value.                                                   |
| `step`          | Steps. (default: 1)                                              |
| `rules`         | Rules that should be applied when **updating** and **creating**. |
| `creationRules` | Rules that should be applied when **creating**.                  |
| `updateRules`   | Rules that should be applied when **updating**.                  |
