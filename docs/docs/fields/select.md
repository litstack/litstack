# Select

A select field.

```php
$form->select('article_type')
    ->title('Type')
    ->options([
        1 => 'News',
        2 => 'Info',
    ])
    ->hint('Select the article type.');
```

## BelongsTo

A select can be used for `belongsTo` relations.

```php
$form->select('article_id')
    ->title('Article')
    ->options(
        Article::all()->mapWithKeys(function($item, $key){
            return [$item->id => $item->title];
        })->toArray()
    )
    ->hint('Select Article.');
```

## Methods

| Methods         | Description                                                            |
| --------------- | ---------------------------------------------------------------------- |
| `title`         | The title description for this field.                                  |
| `options`       | An array with the options, can be a simple array or key => value pairs |
| `hint`          | A short hint that should describe how to use the field.`               |
| `width`         | Width of the field.                                                    |
| `rules`         | Rules that should be applied when **updating** and **creating**.       |
| `creationRules` | Rules that should be applied when **creating**.                        |
| `updateRules`   | Rules that should be applied when **updating**.                        |
