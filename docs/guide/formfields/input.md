# Input

A basic text input field.

Examples:

```php
[
    'id' => 'title',
    'type' => 'input',
    'title' => 'Title',
    'placeholder' => 'title',
    'hint' => 'The Title is shown at the top of the page.',
    'width' => 12,
    'max' => 255, // optional
]
```

```php
[
    'id' => 'length',
    'type' => 'input',
    'title' => 'Length',
    'input_type' => 'number',
    'placeholder' => 'The length in cm',
    'hint' => 'Enter the length in cm',
    'append' => 'cm'
]
```

| Key            | Required | Description                                                                                                                |
| -------------- | -------- | -------------------------------------------------------------------------------------------------------------------------- |
| `id`           | true     | The model key to which the form field belongs.                                                                             |
| `type`         | true     | `input`                                                                                                                    |
| `title`        | true     | The title description for this form field.                                                                                 |
| `placeholder`  | false    | The placeholder for this form field.                                                                                       |
| `input_type`   | false    | Sets an input type for the input                                                                                           |
| `hint`         | false    | A short hint that should describe how to use the form field.                                                               |
| `width`        | false    | Cols of the form field.                                                                                                    |
| `translatable` | false    | Should the form field be translatable? For translatable crud models, the translatable fields are automatically recognized. |
| `max`          | false    | max characters                                                                                                             |
| `prepend`      | false    | prepend the form-field                                                                                                     |
| `append`       | false    | append the form-field                                                                                                      |
