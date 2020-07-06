# Radio

Radio inputs.

```php
$form->radio('type')
    ->title('Type')
    ->options([
        'article' => 'Article',
        'hint' => 'Hint',
    ]);
```

## Methods

| Method          | Description                                                         |
| --------------- | ------------------------------------------------------------------- |
| `title`         | The title description for this field.                               |
| `hint`          | A short hint that should describe how to use the field.`            |
| `width`         | Width of the field.                                                 |
| `options`       | An array with checkboxe values and descriptions.                    |
| `stacked`       | Places each radio one over the other instead of next to each other. |
| `rules`         | Rules that should be applied when **updating** and **creating**.    |
| `creationRules` | Rules that should be applied when **creating**.                     |
| `updateRules`   | Rules that should be applied when **updating**.                     |
