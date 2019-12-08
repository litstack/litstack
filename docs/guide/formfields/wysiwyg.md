# WYSIWYG

A WYSIWYG-editor.

Example

```php
[
    'translatable' => true,
    'id' => 'text',
    'type' => 'wysiwyg',
    'title' => 'Description',
    'hint' => 'The Description for some Object.',
    'width' => 12,
    'max' => 1000, // optional
]
```

| Key            | Required | Description                                                                                                                |
| -------------- | -------- | -------------------------------------------------------------------------------------------------------------------------- |
| `id`           | true     | The model key to which the form field belongs.                                                                             |
| `type`         | true     | `input`                                                                                                                    |
| `title`        | true     | The title description for this form field.                                                                                 |
| `hint`         | false    | A short hint that should describe how to use the form field.`                                                              |
| `width`        | false    | Cols of the form field.                                                                                                    |
| `translatable` | false    | Should the form field be translatable? For translatable crud models, the translatable fields are automatically recognized. |
| `max`          | false    | max characters                                                                                                             |
