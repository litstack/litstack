# DateTime

A DateTime-Picker.

```php
$form->datetime('publish_at')
    ->title('Date')
    ->formatted('l')
    ->hint('Chose a date.')
    ->width(6);
```

```php
$form->dt('publish_at') // dt and datetime work the same
```

## Methods

| Method          | Description                                                      |
| --------------- | ---------------------------------------------------------------- |
| `title`         | The title description for this field.                            |
| `formatted`     | The shown datetime format.                                       |
| `hint`          | A closure where all repeatable blocks are defined.               |
| `width`         | Width of the field.                                              |
| `rules`         | Rules that should be applied when **updating** and **creating**. |
| `creationRules` | Rules that should be applied when **creating**.                  |
| `updateRules`   | Rules that should be applied when **updating**.                  |

## Formats

Usefull formats:

| key  | Format                           |
| ---- | -------------------------------- |
| LT   | 2:15 PM                          |
| LTS  | 2:15:52 PM                       |
| L    | 12/08/2019                       |
| l    | 12/8/2019                        |
| LL   | December 8, 2019                 |
| ll   | Dec 8, 2019                      |
| LLL  | December 8, 2019 2:15 PM         |
| lll  | Dec 8, 2019 2:15 PM              |
| LLLL | Sunday, December 8, 2019 2:15 PM |
| llll | Sun, Dec 8, 2019 2:15 PM         |
