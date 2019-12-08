# Select

A select field.

Examples:

```php
[
    'id' => 'article_type',
    'type' => 'select',
    'title' => 'Type',
    'options' => [
        1 => 'News',
        2 => 'Info',
    ],
    'hint' => 'Select the article type',
    'width' => 5
],
```

```php
[
    'id' => 'article_id',
    'type' => 'select',
    'title' => 'Article',
    'options' => Article::all()->mapWithKeys(function($item, $key){
        return [$item->id => $item->title];
    })->toArray(),
    'hint' => 'Select Article',
    'width' => 4
],
```

The options for a select form field can be set dynamically for a model like this:

```php
<?php

namespace App\Models;

use AwStudio\Fjord\Fjord\Models\Model as FjordModel;
use App\Models\Color;

class Article extends FjordModel
{
    protected $fillable = ['color'];

    public function setColorField($field)
    {
        return $field->options = Color::all()->pluck('color')->toArray();
    }

}
```

| Key       | Required | Description                                                            |
| --------- | -------- | ---------------------------------------------------------------------- |
| `id`      | true     | The model key to which the form field belongs.                         |
| `type`    | true     | `input`                                                                |
| `title`   | true     | The title description for this form field.                             |
| `options` | true     | An array with the options, can be a simple array or key => value pairs |
| `hint`    | false    | A short hint that should describe how to use the form field.`          |
| `width`   | false    | Cols of the form field.                                                |
