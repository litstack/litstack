# Conditional Fields

If fields should depend on the value of other fields, field conditions can be used.

![radio conditions](./screens/conditions/conditions_radio.gif 'radio conditions')

The following example shows an input field that only gets displayed if the select field `type` has the value `news`:

```php{10}
$typeField = $form->select('type')
    ->options([
        'news' => 'News',
        'blog' => 'Blog',
    ])
    ->title('Type');

$form->input('news_title')
    ->title('Title')
    ->when($typeField, 'news');
```

Available conditions:

```php
->when($field, 'value');
->orWhen($field, 'value');
->whenContains($field, 'value');
->orWhenContains($field, 'value');
```

## Conditional Groups

Conditions can also be applied to groups:

```php{4}
$form->group(function($form) {

    $form->input('news_title')->title('Title');
    $form->input('news_text')->title('Text');

})->when($field, 'news');
```

## Multiple Conditions

You may add as many conditions as you like:

```php
$form->input('title')
    ->title('Title')
    ->when($field, 'news')
    ->orWhen($field, 'blog');
```
