# BelongsToMany

A picker for belongsToMany Relations.

Example

```php
[
    'id' => 'staff',
    'model' => App\Models\Employee::class,
    'type' => 'belongsToMany',
    'preview' => [
        [
            'key' => '{fullName}',
            'label' => 'Full Name'
        ],
    ],
    'title' => 'Select Staff',
    'hint' => 'Select Staff',
    'width' => 12,
],
```

| Key       | Required | Description                              |
| --------- | -------- | ---------------------------------------- |
| `id`      | true     | The title of the belongsToMany relation  |
| `type`    | true     | `belongsToMany`                          |
| `model`   | true     | The related model's class                |
| `preview` | true     | Array of fields that should be previewed |
| `title`   | true     | A title for the form                     |
| `hint`    | false    | A hint for the user                      |
| `width`   | false    | The width of the form                    |
