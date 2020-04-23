# WYSIWYG

A **W**hat-**Y**ou-**S**ee-**I**s-**W**hat-You-**G**et editor using [CKEditor](https://ckeditor.com/).

## Example

```php
$form->wysiwyg('text')
    ->translatable()
    ->title('Description')
    ->placeholder('Type something...')
    ->hint('The Description for some Object.')
    ->cols(6);
```

## Methods

| Method         | Description                                                                                                           |
| -------------- | --------------------------------------------------------------------------------------------------------------------- |
| `title`        | The title description for this field.                                                                                 |
| `hint`         | A short hint that should describe how to use the field.`                                                              |
| `cols`         | Cols of the field.                                                                                                    |
| `translatable` | Should the field be translatable? For translatable crud models, the translatable fields are automatically recognized. |
| `placeholder`  | The placeholder for this form field.                                                                                  |
| `max`          | Max characters.                                                                                                       |
